<x-layouts.auth title="Patient Login" footer-text="Patient Portal.">
    <h1 class="text-xl font-bold text-gray-900 text-center">{{ __('Patient Portal') }}</h1>
    <p class="mt-1.5 text-sm text-gray-500 text-center">{{ __('Sign in to view your appointments and receipts.') }}</p>

    <form method="POST" action="{{ route('portal.login.store') }}" class="mt-8 space-y-5">
        @csrf

        <x-form.input :label="__('Phone Number')" name="phone" type="tel" :required="true" autofocus autocomplete="username" />
        <x-form.input :label="__('Password')" name="password" type="password" :required="true" autocomplete="current-password" />

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-surface-300 text-primary-600 focus:ring-primary-500">
            {{ __('Remember me') }}
        </label>

        <x-btn type="submit" variant="primary" class="w-full">{{ __('Sign In') }}</x-btn>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        {{ __("Don't have an account?") }} <a href="{{ route('portal.register') }}" class="font-semibold text-primary-700 hover:text-primary-800">{{ __('Register') }}</a>
    </p>
    <p class="mt-2 text-center text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-primary-700">&larr; {{ __('Back to website') }}</a>
    </p>
</x-layouts.auth>
