<x-layouts.portal title="My Profile">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('My Profile') }}</h1>

    <div class="max-w-xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <form method="POST" action="{{ route('portal.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('Phone Number') }}</label>
                <input type="text" value="{{ $patient->phone }}" disabled class="w-full rounded-lg border border-surface-200 bg-surface-50 px-3.5 py-2.5 text-sm text-gray-500">
                <p class="mt-1.5 text-xs text-gray-500">{{ __('Contact us if you need to change your registered phone number.') }}</p>
            </div>

            <x-form.input :label="__('Full Name')" name="name" :value="$patient->name" :required="true" />
            <x-form.input :label="__('Email Address')" name="email" type="email" :value="$patient->email" />
            <x-form.select
                :label="__('Gender')"
                name="gender"
                :options="['male' => __('Male'), 'female' => __('Female'), 'other' => __('Other')]"
                :value="$patient->gender"
                :placeholder="__('Select gender')"
            />

            <hr class="border-surface-200">

            <p class="text-sm font-medium text-gray-700">{{ __('Change Password') }}</p>
            <x-form.input :label="__('New Password')" name="password" type="password" :hint="__('Leave blank to keep your current password.')" autocomplete="new-password" />
            <x-form.input :label="__('Confirm New Password')" name="password_confirmation" type="password" autocomplete="new-password" />

            <x-btn type="submit" variant="primary">{{ __('Save Changes') }}</x-btn>
        </form>
    </div>
</x-layouts.portal>
