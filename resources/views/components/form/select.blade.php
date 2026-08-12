@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $errorBag = $errors->get($name);
    $inputId = 'field-' . str_replace(['[', ']', '.'], '-', $name);
    $selected = old($name, $value);
@endphp

<div>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <select
        id="{{ $inputId }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-base bg-white ' . ($errorBag ? 'border-red-300' : 'border-surface-300')]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selected === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @foreach ($errorBag as $message)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @endforeach
</div>
