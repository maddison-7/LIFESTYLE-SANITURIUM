<?php

namespace App\Services\Reminders;

interface WhatsAppGatewayInterface
{
    /**
     * Send a WhatsApp text message via the Business API. Return true if
     * the gateway accepted the message for delivery, false if it
     * definitively failed.
     */
    public function send(string $toPhone, string $message): bool;
}
