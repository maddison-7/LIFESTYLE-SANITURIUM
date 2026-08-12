@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'bg-primary-50 text-primary-800 border-primary-200',
        'error' => 'bg-red-50 text-red-800 border-red-200',
        'info' => 'bg-blue-50 text-blue-800 border-blue-200',
    ];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    role="alert"
    {{ $attributes->merge(['class' => 'flex items-start justify-between gap-4 rounded-lg border px-4 py-3 text-sm ' . ($styles[$type] ?? $styles['info'])]) }}
>
    <div>{{ $slot }}</div>
    <button type="button" @click="show = false" class="shrink-0 text-current/60 hover:text-current" aria-label="Dismiss">
        &times;
    </button>
</div>
