@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-full font-bold tracking-[0.02em] transition-all duration-200 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 hover:-translate-y-0.5 active:translate-y-0';

    $variants = [
        'primary' => 'bg-gradient-to-r from-[#0F766E] via-[#0E5E5A] to-[#0B3B3A] text-white border border-white/10 hover:from-[#115E59] hover:via-[#0E5E5A] hover:to-[#0A3232] focus-visible:ring-primary-600 shadow-[0_18px_36px_rgba(12,74,110,0.28)]',
        'secondary' => 'bg-white/95 text-primary-900 border border-primary-200 hover:bg-white hover:border-primary-300 focus-visible:ring-primary-600 shadow-[0_12px_24px_rgba(15,23,42,0.08)]',
        'outline-light' => 'bg-white/5 text-white border border-white/80 hover:bg-white/10 hover:border-white focus-visible:ring-white shadow-[0_12px_24px_rgba(15,23,42,0.12)]',
        'whatsapp' => 'bg-[#25D366] text-white hover:bg-[#1ebc56] focus-visible:ring-[#25D366] shadow-[0_16px_30px_rgba(37,211,102,0.30)]',
        'ghost' => 'bg-transparent text-primary-800 hover:bg-primary-50 hover:text-primary-900 focus-visible:ring-primary-600',
    ];

    $sizes = [
        'sm' => 'px-4 py-2.5 text-sm',
        'md' => 'px-5 py-3 text-sm sm:text-base',
        'lg' => 'px-7 py-3.5 text-base sm:text-lg',
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
