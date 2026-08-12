<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\Reminders\ReminderService;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Send SMS/WhatsApp reminders for confirmed appointments happening soon';

    public function handle(ReminderService $reminders): int
    {
        $targetDate = now()->addHours((int) config('reminders.hours_before'))->toDateString();

        $appointments = Appointment::query()
            ->with(['patient', 'service', 'branch'])
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->whereDate('appointment_date', $targetDate)
            ->whereDoesntHave('reminders', fn ($query) => $query->whereDate('created_at', today()))
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No confirmed appointments on {$targetDate} need a reminder today.");

            return self::SUCCESS;
        }

        foreach ($appointments as $appointment) {
            $reminders->remind($appointment);
            $this->line("Reminded {$appointment->patient->name} ({$appointment->reference}) about their {$targetDate} appointment.");
        }

        $this->info("Sent reminders for {$appointments->count()} appointment(s).");

        return self::SUCCESS;
    }
}
