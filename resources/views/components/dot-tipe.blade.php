@props(['tipe'])

@php
    $warna = match ($tipe) {
        'success' => 'bg-green-500',
        'info' => 'bg-blue-500',
        'warning' => 'bg-amber-500',
        'error' => 'bg-red-500',
        default => 'bg-gray-400',
    };
@endphp

<span {{ $attributes->merge(['class' => "mt-1.5 h-2 w-2 shrink-0 rounded-full {$warna}"]) }} aria-hidden="true"></span>
