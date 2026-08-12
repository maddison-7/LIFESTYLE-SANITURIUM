@php
    $showExport = $showExport ?? true;
    $presets = [
        'Today' => [now()->toDateString(), now()->toDateString()],
        'This Week' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
        'This Month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
    ];
@endphp

<div class="rounded-2xl border border-surface-200 bg-white p-5 mb-6">
    <div class="flex flex-wrap items-center gap-2 mb-4">
        @foreach ($presets as $label => [$presetFrom, $presetTo])
            <a
                href="{{ route($route, ['from' => $presetFrom, 'to' => $presetTo]) }}"
                class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-base {{ $from->toDateString() === $presetFrom && $to->toDateString() === $presetTo ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route($route) }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
            <input type="date" name="from" value="{{ $from->toDateString() }}" class="rounded-lg border border-surface-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
            <input type="date" name="to" value="{{ $to->toDateString() }}" class="rounded-lg border border-surface-300 px-3 py-2 text-sm">
        </div>
        <x-btn type="submit" variant="secondary" size="sm">Apply</x-btn>
        @if ($showExport)
            <x-btn :href="route($route, request()->only(['from', 'to']) + ['export' => 1])" variant="ghost" size="sm">
                Export CSV
            </x-btn>
        @endif
    </form>
</div>
