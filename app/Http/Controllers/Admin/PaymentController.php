<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmPaymentRequest;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with(['patient', 'appointment.service'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('method'), fn ($query) => $query->where('method', $request->string('method')))
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $term = '%'.$request->string('search').'%';
                $query->where('reference', 'like', $term)
                    ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', $term));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'method', 'search']),
            'totalCollected' => Payment::paid()->sum('amount'),
        ]);
    }

    public function show(Payment $payment): View
    {
        $payment->load(['patient', 'appointment.service', 'appointment.branch', 'recordedBy']);

        return view('admin.payments.show', ['payment' => $payment]);
    }

    /**
     * Admin recording a payment already received (cash in hand, mobile
     * money confirmed at the counter, card terminal, etc.) — posts as
     * Paid immediately since the money has already changed hands.
     */
    public function store(StorePaymentRequest $request, Appointment $appointment): RedirectResponse
    {
        $payment = Payment::create([
            ...$request->validated(),
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'gateway' => 'manual',
            'status' => Payment::STATUS_PAID,
            'recorded_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Payment recorded successfully.');
    }

    /**
     * Confirms a payment a patient initiated from the portal (status
     * Pending) once staff have verified the funds actually arrived.
     */
    public function confirm(ConfirmPaymentRequest $request, Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'gateway_reference' => $request->validated('gateway_reference') ?? $payment->gateway_reference,
            'recorded_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment confirmed. A receipt is now available.');
    }

    public function fail(Payment $payment): RedirectResponse
    {
        $payment->update(['status' => Payment::STATUS_FAILED]);

        return back()->with('success', 'Payment marked as failed.');
    }
}
