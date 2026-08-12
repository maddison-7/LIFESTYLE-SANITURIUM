<?php

namespace App\Services\Reminders;

use Illuminate\Support\Facades\Log;

/**
 * Default SMS driver: writes to the log instead of calling a real gateway.
 * No SMS provider credentials are configured for this deployment. To go
 * live, implement SmsGatewayInterface against a real provider (Africa's
 * Talking is the common choice in Tanzania) and swap the binding in
 * AppServiceProvider::register() — see README "SMS & WhatsApp Reminders".
 */
class LogSmsGateway implements SmsGatewayInterface
{
    public function send(string $toPhone, string $message): bool
    {
        Log::channel('reminders')->info("[SMS] to {$toPhone}: {$message}");

        return true;
    }
}
