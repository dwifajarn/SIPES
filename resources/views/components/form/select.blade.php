@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '— Pilih —',
    'required' => false,
])

@php
    $errorMessages = $name ? $errors->get($name) : [];
    $hasError = ! empty($errorMessages);
    $id = $name ? str_replace(['[', ']', '.'], ['_', '', '_'], $name) : null;
    $selectedValue = old($name, $value);
@endphp

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select
        @if ($name) name="{{ $name }}" id="{{ $id }}" @endif
        @if ($required) required aria-required="true" @endif
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        {{ $attributes->only('disabled')->merge([
            'class' => 'block w-full rounded-lg shadow-sm transition sm:text-sm '
                .($hasError
                    ? 'border-red-400 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500'
                    : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'),
        ]) }}
    >
        @if ($placeholder)
            <option value="" @selected(blank($selectedValue)) disabled>{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selectedValue === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    <x-input-error :messages="$errorMessages" :field="$id" />
</div>
