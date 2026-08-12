<?php

namespace App\Services\Reminders;

use App\Models\Appointment;
use App\Models\Reminder;
use Illuminate\Support\Carbon;

class ReminderService
{
    public function __construct(
        private readonly SmsGatewayInterface $sms,
        private readonly WhatsAppGatewayInterface $whatsapp,
    ) {}

    /**
     * Sends both channels and logs one Reminder row per attempt so the
     * appointment detail page has a full history and the scheduled
     * command can tell whether an appointment was already reminded.
     */
    public function remind(Appointment $appointment): void
    {
        $message = $this->buildMessage($appointment);
        $phone = $appointment->patient->phone;

        $smsSent = $this->sms->send($phone, $message);
        Reminder::create([
            'appointment_id' => $appointment->id,
            'channel' => Reminder::CHANNEL_SMS,
            'status' => $smsSent ? Reminder::STATUS_SENT : Reminder::STATUS_FAILED,
            'message' => $message,
            'sent_at' => $smsSent ? now() : null,
        ]);

        $whatsappSent = $this->whatsapp->send($phone, $message);
        Reminder::create([
            'appointment_id' => $appointment->id,
            'channel' => Reminder::CHANNEL_WHATSAPP,
            'status' => $whatsappSent ? Reminder::STATUS_SENT : Reminder::STATUS_FAILED,
            'message' => $message,
            'sent_at' => $whatsappSent ? now() : null,
        ]);
    }

    private function buildMessage(Appointment $appointment): string
    {
        $clinicName = setting('clinic_name', 'Lifestyle Sanitarium Clinic');
        $date = Carbon::parse($appointment->appointment_date)->format('d M Y');
        $time = Carbon::parse($appointment->appointment_time)->format('g:i A');

        return "Hi {$appointment->patient->name}, this is a reminder of your {$appointment->service->name} appointment ".
            "at {$clinicName} ({$appointment->branch->name}) on {$date} at {$time}. Reference: {$appointment->reference}.";
    }
}
