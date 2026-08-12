<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateWebsiteSettingsRequest;
use App\Models\AuditLog;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    private const TEXT_FIELDS = [
        'clinic_name', 'tagline', 'secondary_tagline',
        'phone_primary', 'phone_secondary', 'phone_tertiary', 'whatsapp_number',
        'email', 'address', 'opening_hours', 'google_maps_url',
        'facebook_url', 'instagram_url', 'tiktok_url', 'youtube_url',
        'medical_disclaimer',
    ];

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => WebsiteSetting::allSettings(),
        ]);
    }

    public function update(UpdateWebsiteSettingsRequest $request): RedirectResponse
    {
        foreach (self::TEXT_FIELDS as $field) {
            WebsiteSetting::set($field, $request->validated($field));
        }

        foreach (['logo' => 'logo_path', 'favicon' => 'favicon_path'] as $input => $settingKey) {
            if ($request->hasFile($input)) {
                $oldPath = WebsiteSetting::get($settingKey);
                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file($input)->store('branding', 'public');
                WebsiteSetting::set($settingKey, $path);
            }
        }

        AuditLog::record('settings.updated', 'Updated website settings.');

        return redirect()->route('admin.settings.edit')->with('success', 'Website settings updated successfully.');
    }
}
