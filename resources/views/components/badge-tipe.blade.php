@props(['tipe'])

@php
    $kelas = match ($tipe) {
        'success' => 'bg-green-100 text-green-800',
        'info' => 'bg-blue-100 text-blue-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'error' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-700',
    };

    $label = match ($tipe) {
        'success' => 'Sukses',
        'info' => 'Info',
        'warning' => 'Peringatan',
        'error' => 'Error',
        default => ucfirst($tipe),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {$kelas}"]) }}>
    {{ $label }}
</span>
