<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reminder Gateway Drivers
    |--------------------------------------------------------------------------
    |
    | Both default to "log" — no real SMS or WhatsApp Business API
    | credentials are configured for this deployment, so reminders are
    | written to storage/logs/reminders.log instead of actually being
    | sent. Implement SmsGatewayInterface / WhatsAppGatewayInterface
    | against a real provider, bind it in AppServiceProvider::register(),
    | and set these to a driver name of your choosing. See README
    | "SMS & WhatsApp Reminders".
    |
    */

    'sms_driver' => env('SMS_DRIVER', 'log'),
    'whatsapp_driver' => env('WHATSAPP_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Reminder Timing
    |--------------------------------------------------------------------------
    |
    | How many hours before an appointment the reminder command should
    | fire. Only Confirmed appointments are reminded — a Pending request
    | hasn't been confirmed by staff yet, so reminding about it would be
    | premature.
    |
    */

    'hours_before' => env('REMINDER_HOURS_BEFORE', 24),

];
