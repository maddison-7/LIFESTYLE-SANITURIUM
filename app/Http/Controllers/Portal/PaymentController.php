<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StorePaymentDeclarationRequest;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * The patient declares a payment they've already sent (e.g. mobile
     * money transferred to the clinic's number) — this is not a live
     * payment gateway charge. It lands as Pending and a staff member
     * verifies the funds arrived before confirming it from the admin
     * dashboard, at which point a receipt becomes available.
     */
    public function store(StorePaymentDeclarationRequest $request, Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->patient_id === Auth::guard('patient')->id(), 404);

        $payment = Payment::create([
            ...$request->validated(),
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'gateway' => 'manual',
            'status' => Payment::STATUS_PENDING,
        ]);

        Notification::notifyRoles(
            [User::ROLE_SUPER_ADMIN, User::ROLE_CLINIC_ADMIN, User::ROLE_RECEPTIONIST],
            'Payment Awaiting Confirmation',
            "{$appointment->patient->name} declared a payment of {$payment->amount} for {$appointment->reference}. Please verify and confirm.",
            Notification::TYPE_SYSTEM,
        );

        return back()->with('success', 'Thank you. Your payment has been submitted and is awaiting confirmation from our team.');
    }
}
