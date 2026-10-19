@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => true,
    'autoDismiss' => null,
])

@php
    $varian = [
        'success' => [
            'wrapper' => 'bg-green-50 border-green-500 text-green-900',
            'icon' => 'text-green-600',
            'title' => 'text-green-900',
            'badge' => 'Tersimpan',
            'path' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
        ],
        'info' => [
            'wrapper' => 'bg-blue-50 border-blue-500 text-blue-900',
            'icon' => 'text-blue-600',
            'title' => 'text-blue-900',
            'badge' => 'Informasi',
            'path' => 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z',
        ],
        'warning' => [
            'wrapper' => 'bg-amber-50 border-amber-500 text-amber-900',
            'icon' => 'text-amber-600',
            'title' => 'text-amber-900',
            'badge' => 'Peringatan',
            'path' => 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
        ],
        'error' => [
            'wrapper' => 'bg-red-50 border-red-500 text-red-900',
            'icon' => 'text-red-600',
            'title' => 'text-red-900',
            'badge' => 'Kesalahan',
            'path' => 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
        ],
    ];

    $gaya = $varian[$type] ?? $varian['info'];
    $autoDismiss = $autoDismiss ?? ($type === 'success' ? 4000 : null);
@endphp

<div
    {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-lg border-l-4 p-4 shadow-sm {$gaya['wrapper']}"]) }}
    role="alert"
    @if ($autoDismiss) x-data="{ tampil: true }" x-show="tampil" x-init="setTimeout(() => tampil = false, {{ $autoDismiss }})" x-transition @endif
>
    <svg class="h-5 w-5 shrink-0 {{ $gaya['icon'] }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path fill-rule="evenodd" d="{{ $gaya['path'] }}" clip-rule="evenodd" />
    </svg>

    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="text-sm font-semibold {{ $gaya['title'] }}">{{ $title }}</p>
        @endif

        <div class="text-sm leading-relaxed">{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            class="-m-1 shrink-0 rounded p-1 opacity-60 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-current"
            onclick="this.closest('[role=alert]').remove()"
            aria-label="Tutup pesan"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
