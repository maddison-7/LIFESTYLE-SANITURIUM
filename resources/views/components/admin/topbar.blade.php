@props(['title' => 'Dashboard'])

@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'clinic_admin' => 'Clinic Admin',
        'receptionist' => 'Receptionist',
        'healthcare_staff' => 'Healthcare Staff',
    ];
    $user = auth()->user();
    $unreadCount = $user->notifications()->unread()->count();
@endphp

<header class="sticky top-0 z-30 flex h-16 items-center justify-between gap-4 border-b border-surface-200 bg-white px-4 sm:px-6">
    <div class="flex items-center gap-3">
        <button
            @click="sidebarOpen = true"
            type="button"
            class="lg:hidden inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-surface-100"
            aria-label="Open menu"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <h1 class="text-lg font-bold text-gray-900">{{ $title }}</h1>
    </div>

    <div class="flex items-center gap-2">
        <x-theme-toggle />

        <a
            href="{{ route('admin.notifications.index') }}"
            class="relative inline-flex items-center justify-center rounded-lg p-2.5 text-gray-600 hover:bg-surface-100"
            aria-label="Notifications"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
            @if ($unreadCount > 0)
                <span class="absolute top-1 right-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </a>

        <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" type="button" class="flex items-center gap-2.5 rounded-lg py-1.5 pl-1.5 pr-2.5 hover:bg-surface-100" :aria-expanded="open">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-700 font-semibold text-sm">
                {{ Str::of($user->name)->substr(0, 1)->upper() }}
            </span>
            <span class="hidden sm:block text-left">
                <span class="block text-sm font-semibold text-gray-900 leading-tight">{{ $user->name }}</span>
                <span class="block text-xs text-gray-500 leading-tight">{{ $roleLabels[$user->role] ?? $user->role }}</span>
            </span>
        </button>

        <div
            x-cloak
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="absolute right-0 mt-2 w-48 rounded-lg border border-surface-200 bg-white py-1.5 shadow-lg"
        >
            <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">
                View Public Site
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">
                    Logout
                </button>
            </form>
        </div>
        </div>
    </div>
</header>
