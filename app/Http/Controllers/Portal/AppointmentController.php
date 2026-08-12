<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Auth::guard('patient')->user()
            ->appointments()
            ->with(['service', 'branch'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(10);

        return view('portal.appointments.index', ['appointments' => $appointments]);
    }

    public function show(Appointment $appointment): View
    {
        abort_unless($appointment->patient_id === Auth::guard('patient')->id(), 404);

        $appointment->load(['service', 'branch', 'payments']);

        return view('portal.appointments.show', ['appointment' => $appointment]);
    }
}
