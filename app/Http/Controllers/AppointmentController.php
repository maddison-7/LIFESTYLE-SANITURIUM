<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function create(Request $request): View
    {
        $preselectedService = Service::active()
            ->where('slug', $request->query('service'))
            ->first();

        return view('appointments.create', [
            'services' => Service::active()->ordered()->get(),
            'branches' => Branch::visible()->ordered()->get(),
            'preselectedService' => $preselectedService,
        ]);
    }

    /**
     * Persist the request as Pending. Nothing here auto-confirms an
     * appointment — a human on staff reviews and confirms it from the
     * admin dashboard.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $patient = Patient::query()->updateOrCreate(
            ['phone' => $request->validated('phone')],
            [
                'name' => $request->validated('full_name'),
                'email' => $request->validated('email'),
                'gender' => $request->validated('gender'),
            ]
        );

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'service_id' => $request->validated('service_id'),
            'branch_id' => $request->validated('branch_id'),
            'appointment_date' => $request->validated('appointment_date'),
            'appointment_time' => $request->validated('appointment_time'),
            'message' => $request->validated('message'),
            'status' => Appointment::STATUS_PENDING,
        ]);

        Notification::notifyRoles(
            [User::ROLE_SUPER_ADMIN, User::ROLE_CLINIC_ADMIN, User::ROLE_RECEPTIONIST],
            'New Appointment Request',
            "{$patient->name} requested an appointment for {$appointment->service->name} on {$appointment->appointment_date->format('d M Y')}.",
            Notification::TYPE_APPOINTMENT,
        );

        return redirect()
            ->route('appointments.create')
            ->with('success', "Thank you. Your appointment request has been received. Our team will contact you to confirm your appointment. Your reference number is {$appointment->reference}.");
    }
}
