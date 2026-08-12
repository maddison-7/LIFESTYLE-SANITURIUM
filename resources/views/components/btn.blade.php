@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-base focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';

    $variants = [
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus-visible:ring-primary-600 shadow-sm',
        'secondary' => 'bg-white text-primary-700 border border-primary-200 hover:bg-primary-50 focus-visible:ring-primary-600',
        'outline-light' => 'bg-transparent text-white border border-white/70 hover:bg-white/10 focus-visible:ring-white',
        'whatsapp' => 'bg-[#25D366] text-white hover:bg-[#1ebe57] focus-visible:ring-[#25D366] shadow-sm',
        'ghost' => 'bg-transparent text-primary-700 hover:bg-primary-50 focus-visible:ring-primary-600',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-3 text-sm sm:text-base',
        'lg' => 'px-7 py-4 text-base sm:text-lg',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" {{ $attributes->except('type')->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
