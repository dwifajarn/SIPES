@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'helper' => null,
    'required' => false,
    'hint' => null,
    'realtime' => false,
    'counter' => null,
])

@php
    $errorMessages = $name ? $errors->get($name) : [];
    $hasError = ! empty($errorMessages);
    $id = $name ? str_replace(['[', ']', '.'], ['_', '', '_'], $name) : null;
@endphp

<div {{ $attributes->only('class') }}>
    @if ($label)
        <div class="flex items-center justify-between mb-1.5">
            <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500" aria-hidden="true">*</span>
                @endif
            </label>
            @if ($counter)
                <span class="text-[11px] text-gray-400 font-mono" x-text="{{ $counter }}"></span>
            @endif
        </div>
    @endif

    <div class="relative">
        <input
            type="{{ $type }}"
            @if ($name) name="{{ $name }}" id="{{ $id }}" @endif
            value="{{ old($name, $value) }}"
            @if ($required) required aria-required="true" @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            @if ($realtime)
                :class="{
                    'border-red-400 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-500': hasClientError('{{ $name }}'),
                    'border-green-400 focus:border-green-500 focus:ring-green-500': isClientValid('{{ $name }}')
                }"
            @endif
            {{ $attributes->except('class')->merge([
                'class' => 'block w-full rounded-lg shadow-sm transition sm:text-sm '
                    .($hasError
                        ? 'border-red-400 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500'
                        : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500'),
            ]) }}
        />

        @if ($realtime)
            <span
                x-show="isClientValid('{{ $name }}')"
                x-cloak
                class="absolute right-3 top-2.5 text-green-600 pointer-events-none"
                title="Format valid"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </div>

    @if ($realtime)
        <div x-show="hasClientError('{{ $name }}')" x-cloak class="mt-1.5 flex items-start gap-1 text-xs text-red-600 font-medium">
            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span x-text="getClientError('{{ $name }}')"></span>
        </div>
    @endif

    @if ($helper && ! $hasError)
        <p
            class="mt-1.5 text-xs text-gray-500"
            id="{{ $id }}-helper"
            @if ($realtime) x-show="!hasClientError('{{ $name }}')" @endif
        >
            {{ $helper }}
        </p>
    @endif

    <x-input-error :messages="$errorMessages" :field="$id" />
</div>
