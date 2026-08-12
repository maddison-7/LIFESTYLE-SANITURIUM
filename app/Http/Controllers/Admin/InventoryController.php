<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInventoryTransactionRequest;
use App\Models\InventoryTransaction;
use App\Models\Medicine;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $medicines = Medicine::query()->orderBy('name')->get();

        return view('admin.inventory.index', [
            'medicines' => $medicines,
            'lowStockCount' => $medicines->filter(fn (Medicine $medicine) => $medicine->isLowStock())->count(),
            'outOfStockCount' => $medicines->where('quantity', 0)->count(),
        ]);
    }

    public function history(Request $request): View
    {
        $transactions = InventoryTransaction::query()
            ->with(['medicine', 'user'])
            ->when($request->filled('medicine_id'), fn ($query) => $query->where('medicine_id', $request->integer('medicine_id')))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('created_at', $request->date('date')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.inventory.history', [
            'transactions' => $transactions,
            'medicines' => Medicine::query()->orderBy('name')->get(),
            'filters' => $request->only(['medicine_id', 'type', 'date']),
        ]);
    }

    /**
     * Shared by the medicine detail page's quick Stock In/Out modal and the
     * general movement form on the Inventory overview. Every change to a
     * medicine's quantity is logged here, never edited directly.
     */
    public function store(StoreInventoryTransactionRequest $request): RedirectResponse
    {
        $medicine = Medicine::findOrFail($request->validated('medicine_id'));
        $quantity = $request->validated('quantity');
        $type = $request->validated('type');
        $wasLowStock = $medicine->isLowStock();

        DB::transaction(function () use ($medicine, $type, $quantity, $request) {
            InventoryTransaction::create([
                'medicine_id' => $medicine->id,
                'type' => $type,
                'quantity' => $quantity,
                'reference' => $request->validated('reference'),
                'user_id' => Auth::id(),
            ]);

            $medicine->increment('quantity', $type === InventoryTransaction::TYPE_IN ? $quantity : -$quantity);
        });

        $medicine->refresh();

        if (! $wasLowStock && $medicine->isLowStock()) {
            Notification::notifyRoles(
                [User::ROLE_SUPER_ADMIN, User::ROLE_CLINIC_ADMIN],
                'Low Stock Alert',
                "{$medicine->name} is low on stock ({$medicine->quantity} units remaining).",
                Notification::TYPE_INVENTORY,
            );
        }

        $verb = $type === InventoryTransaction::TYPE_IN ? 'added to' : 'removed from';

        return back()->with('success', "{$quantity} units {$verb} {$medicine->name}. New stock: {$medicine->quantity}.");
    }
}
