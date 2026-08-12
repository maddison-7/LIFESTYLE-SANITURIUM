<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\HealthArticle;
use App\Models\HealthcareStaff;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $branchId = $user->isBranchScoped() ? $user->branch_id : null;

        $appointmentQuery = fn () => Appointment::query()->when($branchId, fn ($q) => $q->where('branch_id', $branchId));

        return view('admin.dashboard', [
            'stats' => [
                'appointments_today' => $appointmentQuery()->whereDate('appointment_date', today())->count(),
                'pending_appointments' => $appointmentQuery()->where('status', Appointment::STATUS_PENDING)->count(),
                'confirmed_appointments' => $appointmentQuery()->where('status', Appointment::STATUS_CONFIRMED)->count(),
                'completed_appointments' => $appointmentQuery()->where('status', Appointment::STATUS_COMPLETED)->count(),
                'total_patients' => Patient::count(),
                'total_services' => Service::count(),
                'healthcare_staff' => HealthcareStaff::count(),
                'health_articles' => HealthArticle::count(),
            ],
            'branchScoped' => $user->isBranchScoped(),
            'branchName' => $branchId ? $user->branch?->name : null,
        ]);
    }
}
