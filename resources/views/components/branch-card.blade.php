@props(['branch'])

<article class="rounded-2xl border border-surface-200 bg-white p-6 shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
            </svg>
        </div>
        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $branch->isOpen() ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700' }}">
            {{ __($branch->statusLabel()) }}
        </span>
    </div>

    <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $branch->name }}</h3>
    <p class="mt-1 text-sm text-gray-600">{{ $branch->location }}</p>

    @if ($branch->opening_hours)
        <p class="mt-3 text-sm text-gray-500">{{ $branch->opening_hours }}</p>
    @endif

    <div class="mt-5 flex flex-wrap gap-3">
        @if ($branch->phone)
            <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 hover:text-primary-800">
                {{ __('Call :phone', ['phone' => $branch->phone]) }}
            </a>
        @endif
        @if ($branch->map_link)
            <a href="{{ $branch->map_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 hover:text-primary-800">
                {{ __('View on Map') }}
            </a>
        @endif
    </div>
</article>
