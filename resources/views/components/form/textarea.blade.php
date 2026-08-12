@props([
    'label' => null,
    'name',
    'value' => null,
    'required' => false,
    'rows' => 4,
    'hint' => null,
])

@php
    $errorBag = $errors->get($name);
    $inputId = 'field-' . str_replace(['[', ']', '.'], '-', $name);
@endphp

<div>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <textarea
        id="{{ $inputId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-base ' . ($errorBag ? 'border-red-300' : 'border-surface-300')]) }}
    >{{ old($name, $value) }}</textarea>

    @if ($hint && !$errorBag)
        <p class="mt-1.5 text-xs text-gray-500">{{ $hint }}</p>
    @endif

    @foreach ($errorBag as $message)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @endforeach
</div>
