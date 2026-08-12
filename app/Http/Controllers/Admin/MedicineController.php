<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMedicineRequest;
use App\Http\Requests\Admin\UpdateMedicineRequest;
use App\Models\InventoryTransaction;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $medicines = Medicine::query()
            ->search($request->query('search'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->boolean('low_stock'), fn ($query) => $query->lowStock())
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.medicines.index', [
            'medicines' => $medicines,
            'categories' => Medicine::query()->pluck('category')->unique()->sort()->values(),
            'filters' => $request->only(['search', 'category', 'status', 'low_stock']),
        ]);
    }

    public function create(): View
    {
        return view('admin.medicines.create', [
            'medicine' => null,
            'categories' => Medicine::query()->pluck('category')->unique()->sort()->values(),
        ]);
    }

    public function store(StoreMedicineRequest $request): RedirectResponse
    {
        $medicine = Medicine::create($request->validated());

        if ($medicine->quantity > 0) {
            InventoryTransaction::create([
                'medicine_id' => $medicine->id,
                'type' => InventoryTransaction::TYPE_IN,
                'quantity' => $medicine->quantity,
                'reference' => 'Opening stock',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.medicines.show', $medicine)->with('success', 'Medicine added successfully.');
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load(['transactions' => function ($query) {
            $query->with('user')->latest();
        }]);

        return view('admin.medicines.show', ['medicine' => $medicine]);
    }

    public function edit(Medicine $medicine): View
    {
        return view('admin.medicines.edit', [
            'medicine' => $medicine,
            'categories' => Medicine::query()->pluck('category')->unique()->sort()->values(),
        ]);
    }

    public function update(UpdateMedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $medicine->update($request->validated());

        return redirect()->route('admin.medicines.show', $medicine)->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        if ($medicine->transactions()->exists()) {
            return back()->with('error', 'This medicine has stock movement history and cannot be deleted. Deactivate it instead.');
        }

        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted.');
    }
}
