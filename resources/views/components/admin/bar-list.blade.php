@props([
    'items',
    'emptyMessage' => 'No data available for this period.',
])

@php
    $max = collect($items)->max('value') ?: 1;
@endphp

@if (empty($items))
    <p class="text-sm text-gray-500 text-center py-8">{{ $emptyMessage }}</p>
@else
    <div class="space-y-3.5">
        @foreach ($items as $item)
            <div>
                <div class="flex items-center justify-between gap-3 text-sm mb-1.5">
                    <span class="text-gray-700 truncate">{{ $item['label'] }}</span>
                    <span class="font-semibold text-gray-900 tabular-nums shrink-0">{{ $item['value'] }}</span>
                </div>
                <div class="h-2 rounded-full bg-surface-100 overflow-hidden">
                    <div
                        class="h-full rounded-full {{ $item['color'] ?? 'bg-primary-600' }}"
                        style="width: {{ $max > 0 ? max(round(($item['value'] / $max) * 100), $item['value'] > 0 ? 3 : 0) : 0 }}%"
                    ></div>
                </div>
            </div>
        @endforeach
    </div>
@endif
