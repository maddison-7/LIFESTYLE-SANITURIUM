@php
    $currentLocale = app()->getLocale();
@endphp

<div class="inline-flex items-center rounded-lg border border-surface-200 dark:border-surface-700 p-0.5 text-xs font-semibold" role="group" aria-label="{{ __('Language') }}">
    @foreach (config('localization.available_locales') as $code => $label)
        <a
            href="{{ route('locale.switch', $code) }}"
            class="px-2 py-1 rounded-md transition-base {{ $currentLocale === $code ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-surface-100' }}"
            @if ($currentLocale === $code) aria-current="true" @endif
        >
            {{ strtoupper($code) }}
        </a>
    @endforeach
</div>
