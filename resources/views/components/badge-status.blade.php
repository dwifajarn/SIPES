@props(['status'])

@php
    $aktif = $status === 'aktif';
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium '
        .($aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700'),
]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $aktif ? 'bg-green-500' : 'bg-gray-400' }}"></span>
    {{ $aktif ? 'Aktif' : 'Nonaktif' }}
</span>
