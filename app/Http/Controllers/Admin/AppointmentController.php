<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Service;
use App\Services\Reminders\ReminderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $appointments = Appointment::query()
            ->with(['patient', 'service', 'branch'])
            ->search($request->query('search'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($user->isBranchScoped(), fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when(! $user->isBranchScoped() && $request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('service_id'), fn ($query) => $query->where('service_id', $request->integer('service_id')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('appointment_date', $request->date('date')))
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'branches' => Branch::ordered()->get(),
            'services' => Service::ordered()->get(),
            'filters' => $request->only(['search', 'status', 'branch_id', 'service_id', 'date']),
            'branchScoped' => $user->isBranchScoped(),
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $this->authorizeBranchAccess($appointment);

        $appointment->load(['patient', 'service', 'branch', 'payments', 'reminders' => fn ($query) => $query->latest()]);

        return view('admin.appointments.show', ['appointment' => $appointment]);
    }

    public function sendReminder(ReminderService $reminders, Appointment $appointment): RedirectResponse
    {
        $this->authorizeBranchAccess($appointment);

        $reminders->remind($appointment);

        return back()->with('success', 'Reminder sent via SMS and WhatsApp.');
    }

    /**
     * Route-model binding alone doesn't stop a branch-scoped receptionist
     * from opening another branch's appointment by guessing the URL — the
     * index listing filters what they see, this closes the direct-link gap.
     */
    private function authorizeBranchAccess(Appointment $appointment): void
    {
        $user = Auth::user();

        abort_if($user->isBranchScoped() && $appointment->branch_id !== $user->branch_id, 404);
    }

    /**
     * Handles both rescheduling and admin-notes updates from the same form
     * on the appointment detail page. Changing the date/time automatically
     * flips status to Rescheduled unless the appointment is already closed out.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeBranchAccess($appointment);

        $dateChanged = $appointment->appointment_date->toDateString() !== $request->validated('appointment_date')
            || $appointment->appointment_time !== $request->validated('appointment_time');

        $appointment->appointment_date = $request->validated('appointment_date');
        $appointment->appointment_time = $request->validated('appointment_time');
        $appointment->admin_notes = $request->validated('admin_notes');

        if ($dateChanged && ! in_array($appointment->status, [Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED], true)) {
            $appointment->status = Appointment::STATUS_RESCHEDULED;
        }

        $appointment->save();

        return back()->with('success', 'Appointment updated successfully.');
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeBranchAccess($appointment);

        $appointment->update(['status' => $request->validated('status')]);

        return back()->with('success', 'Appointment marked as '.$appointment->statusLabel().'.');
    }
}
