<x-layouts.auth title="Patient Registration" footer-text="Patient Portal.">
    <h1 class="text-xl font-bold text-gray-900 text-center">{{ __('Create Your Account') }}</h1>
    <p class="mt-1.5 text-sm text-gray-500 text-center">{{ __('Track your appointments and receipts online.') }}</p>

    <form method="POST" action="{{ route('portal.register.store') }}" class="mt-8 space-y-5">
        @csrf

        <x-form.input :label="__('Full Name')" name="name" :required="true" autofocus />
        <x-form.input
            :label="__('Phone Number')"
            name="phone"
            type="tel"
            :required="true"
            autocomplete="username"
            :hint="__(\"If you've booked with us before, use the same number to link your history.\")"
        />
        <x-form.input :label="__('Email Address')" name="email" type="email" :hint="__('Optional')" />

        <x-form.select
            :label="__('Gender')"
            name="gender"
            :options="['male' => __('Male'), 'female' => __('Female'), 'other' => __('Other')]"
            :placeholder="__('Select gender')"
        />

        <x-form.input :label="__('Password')" name="password" type="password" :required="true" :hint="__('Minimum 8 characters.')" autocomplete="new-password" />
        <x-form.input :label="__('Confirm Password')" name="password_confirmation" type="password" :required="true" autocomplete="new-password" />

        <x-btn type="submit" variant="primary" class="w-full">{{ __('Create Account') }}</x-btn>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        {{ __('Already have an account?') }} <a href="{{ route('portal.login') }}" class="font-semibold text-primary-700 hover:text-primary-800">{{ __('Sign in') }}</a>
    </p>
</x-layouts.auth>
