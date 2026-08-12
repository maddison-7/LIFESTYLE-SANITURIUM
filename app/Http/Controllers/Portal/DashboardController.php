<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $patient = Auth::guard('patient')->user();

        $upcoming = $patient->appointments()
            ->with(['service', 'branch'])
            ->whereIn('status', ['pending', 'confirmed', 'rescheduled'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $recentPayments = $patient->payments()->with('appointment.service')->latest()->take(5)->get();

        return view('portal.dashboard', [
            'patient' => $patient,
            'upcoming' => $upcoming,
            'totalAppointments' => $patient->appointments()->count(),
            'recentPayments' => $recentPayments,
        ]);
    }
}
