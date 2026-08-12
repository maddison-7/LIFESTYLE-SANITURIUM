<?php

namespace App\Services\Reminders;

interface SmsGatewayInterface
{
    /**
     * Send a plain-text SMS. Return true if the gateway accepted the
     * message for delivery, false if it definitively failed.
     */
    public function send(string $toPhone, string $message): bool;
}
