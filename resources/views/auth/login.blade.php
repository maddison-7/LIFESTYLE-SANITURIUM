<x-layouts.auth title="Admin Login">
    <h1 class="text-xl font-bold text-gray-900 text-center">Staff & Admin Login</h1>
    <p class="mt-1.5 text-sm text-gray-500 text-center">Sign in to manage the clinic dashboard.</p>

    <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
        @csrf

        <x-form.input
            label="Email Address"
            name="email"
            type="email"
            :required="true"
            autofocus
            autocomplete="username"
        />

        <x-form.input
            label="Password"
            name="password"
            type="password"
            :required="true"
            autocomplete="current-password"
        />

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-surface-300 text-primary-600 focus:ring-primary-500">
            Remember me
        </label>

        <x-btn type="submit" variant="primary" class="w-full">
            Sign In
        </x-btn>
    </form>
</x-layouts.auth>
