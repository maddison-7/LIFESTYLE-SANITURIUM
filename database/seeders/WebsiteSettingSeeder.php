<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Seed only the clinic details explicitly provided by the client.
     * Anything not supplied (email, opening hours, socials, map coordinates)
     * is left blank so it can be filled in later from the admin dashboard.
     */
    public function run(): void
    {
        $settings = [
            'clinic_name' => 'Lifestyle Sanitarium Clinic',
            'tagline' => 'Afya Bora, Maisha Bora.',
            'secondary_tagline' => 'Professional • Confidential • Patient-Centred Care',

            'phone_primary' => '0713 999 255',
            'phone_secondary' => '0767 999 255',
            'phone_tertiary' => '0787 882 864',
            'whatsapp_number' => '255713999255',

            'email' => '',
            'address' => 'Buguruni Malapa, Dar es Salaam, Tanzania',
            'opening_hours' => '',
            'google_maps_url' => '',

            'facebook_url' => '',
            'instagram_url' => '',
            'tiktok_url' => '',
            'youtube_url' => '',

            'logo_path' => '',
            'favicon_path' => '',

            'medical_disclaimer' => 'Information provided on this website is intended for general health education and information about our services. It is not a substitute for professional medical consultation, diagnosis or treatment. Please contact Lifestyle Sanitarium Clinic for professional medical advice.',
        ];

        foreach ($settings as $key => $value) {
            WebsiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
