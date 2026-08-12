@php
    $typeIcons = [
        'appointment' => 'M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5Z',
        'inventory' => 'M20.25 7.5 12 3 3.75 7.5m16.5 0-16.5 0m16.5 0v9L12 21m-8.25-4.5V7.5M12 21l-8.25-4.5M12 21v-9m0 0L3.75 7.5M12 12l8.25-4.5',
        'system' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94',
    ];
@endphp

<x-layouts.admin title="Notifications">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex gap-2">
            <a
                href="{{ route('admin.notifications.index') }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ !($filters['status'] ?? null) ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
            >
                All
            </a>
            <a
                href="{{ route('admin.notifications.index', ['status' => 'unread']) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ ($filters['status'] ?? null) === 'unread' ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
            >
                Unread
            </a>
        </div>

        <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                Mark all as read
            </button>
        </form>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="divide-y divide-surface-100">
            @forelse ($notifications as $notification)
                <div class="flex items-start gap-4 px-6 py-4 {{ $notification->status === 'unread' ? 'bg-primary-50/40' : '' }}">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $typeIcons[$notification->type] ?? $typeIcons['system'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-900">{{ $notification->title }}</p>
                            @if ($notification->status === 'unread')
                                <span class="h-2 w-2 rounded-full bg-primary-600"></span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                        <p class="mt-1.5 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if ($notification->status === 'unread')
                        <form method="POST" action="{{ route('admin.notifications.markRead', $notification) }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-primary-700 hover:text-primary-800 whitespace-nowrap">
                                Mark read
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="px-6 py-16 text-center text-gray-500">No notifications yet.</div>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
