<?php

use App\Models\WebsiteSetting;

if (! function_exists('setting')) {
    /**
     * Fetch a website setting by key, falling back to $default when unset.
     */
    function setting(string $key, ?string $default = null): ?string
    {
        return WebsiteSetting::get($key, $default);
    }
}

if (! function_exists('whatsapp_link')) {
    /**
     * Build a wa.me link using the clinic's WhatsApp number and an optional message.
     */
    function whatsapp_link(?string $message = null): string
    {
        $number = preg_replace('/\D+/', '', setting('whatsapp_number', '255713999255'));
        $message ??= 'Hello Lifestyle Sanitarium Clinic, I would like to know more about your services and book an appointment.';

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
