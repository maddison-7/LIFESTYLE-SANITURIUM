<?php

namespace App\Services\Reminders;

use Illuminate\Support\Facades\Log;

/**
 * Default WhatsApp driver: writes to the log instead of calling the
 * WhatsApp Business Cloud API. No Meta Business API credentials are
 * configured for this deployment. To go live, implement
 * WhatsAppGatewayInterface against the real Cloud API and swap the
 * binding in AppServiceProvider::register() — see README "SMS & WhatsApp
 * Reminders". This is separate from the existing "Chat on WhatsApp"
 * wa.me links used elsewhere in the app, which need no API at all.
 */
class LogWhatsAppGateway implements WhatsAppGatewayInterface
{
    public function send(string $toPhone, string $message): bool
    {
        Log::channel('reminders')->info("[WhatsApp] to {$toPhone}: {$message}");

        return true;
    }
}
