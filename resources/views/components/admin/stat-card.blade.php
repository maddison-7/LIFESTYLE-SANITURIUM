@props(['label', 'value', 'icon', 'hint' => null])

<div class="rounded-2xl border border-surface-200 bg-white p-5">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs text-gray-400">{{ $hint }}</p>
            @endif
        </div>
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                @foreach (explode('|', $icon) as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                @endforeach
            </svg>
        </div>
    </div>
</div>
