<x-layouts.admin title="Website Settings">
    <p class="text-sm text-gray-500 mb-6">
        These details power the public website's navbar, footer, contact page and WhatsApp button.
    </p>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <h2 class="text-base font-bold text-gray-900">Clinic Information</h2>
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form.input label="Clinic Name" name="clinic_name" :value="$settings['clinic_name'] ?? ''" :required="true" />
                <x-form.input label="Tagline" name="tagline" :value="$settings['tagline'] ?? ''" />
                <div class="sm:col-span-2">
                    <x-form.input label="Secondary Tagline" name="secondary_tagline" :value="$settings['secondary_tagline'] ?? ''" />
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <h2 class="text-base font-bold text-gray-900">Contact Details</h2>
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form.input label="Primary Phone" name="phone_primary" :value="$settings['phone_primary'] ?? ''" />
                <x-form.input label="Secondary Phone" name="phone_secondary" :value="$settings['phone_secondary'] ?? ''" />
                <x-form.input label="Tertiary Phone" name="phone_tertiary" :value="$settings['phone_tertiary'] ?? ''" />
                <x-form.input
                    label="WhatsApp Number"
                    name="whatsapp_number"
                    :value="$settings['whatsapp_number'] ?? ''"
                    :required="true"
                    hint="Digits only, with country code, e.g. 255713999255"
                />
                <x-form.input label="Email Address" name="email" type="email" :value="$settings['email'] ?? ''" hint="Leave blank if not yet available." />
                <x-form.input label="Google Maps URL" name="google_maps_url" :value="$settings['google_maps_url'] ?? ''" hint="Embed URL for the Contact page map." />
                <div class="sm:col-span-2">
                    <x-form.input label="Main Address" name="address" :value="$settings['address'] ?? ''" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.input label="Opening Hours" name="opening_hours" :value="$settings['opening_hours'] ?? ''" hint="Leave blank if not yet finalised." />
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <h2 class="text-base font-bold text-gray-900">Social Media</h2>
            <p class="mt-1 text-sm text-gray-500">Leave blank until official accounts exist — links only appear on the site once set.</p>
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-form.input label="Facebook URL" name="facebook_url" :value="$settings['facebook_url'] ?? ''" />
                <x-form.input label="Instagram URL" name="instagram_url" :value="$settings['instagram_url'] ?? ''" />
                <x-form.input label="TikTok URL" name="tiktok_url" :value="$settings['tiktok_url'] ?? ''" />
                <x-form.input label="YouTube URL" name="youtube_url" :value="$settings['youtube_url'] ?? ''" />
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <h2 class="text-base font-bold text-gray-900">Branding</h2>
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo</label>
                    @if (!empty($settings['logo_path'] ?? null))
                        <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Current logo" class="h-12 w-auto mb-3">
                    @endif
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                    @error('logo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Favicon</label>
                    @if (!empty($settings['favicon_path'] ?? null))
                        <img src="{{ asset('storage/' . $settings['favicon_path']) }}" alt="Current favicon" class="h-8 w-auto mb-3">
                    @endif
                    <input type="file" name="favicon" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                    @error('favicon') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <h2 class="text-base font-bold text-gray-900">Medical Disclaimer</h2>
            <p class="mt-1 text-sm text-gray-500">Shown in the site footer and About page.</p>
            <div class="mt-5">
                <textarea
                    name="medical_disclaimer"
                    rows="4"
                    class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >{{ old('medical_disclaimer', $settings['medical_disclaimer'] ?? '') }}</textarea>
                @error('medical_disclaimer') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <x-btn type="submit" variant="primary">
            Save Settings
        </x-btn>
    </form>
</x-layouts.admin>
