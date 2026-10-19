@php
    $kode = $code ?? 404;
    $judul = match ($kode) {
        403 => 'Akses Ditolak',
        404 => 'Halaman Tidak Ditemukan',
        419 => 'Sesi Kedaluwarsa',
        429 => 'Terlalu Banyak Permintaan',
        500, 503 => 'Terjadi Kesalahan Sistem',
        default => 'Terjadi Kesalahan',
    };

    $warna = match ($kode) {
        403 => 'text-amber-500',
        404 => 'text-indigo-500',
        default => 'text-red-500',
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $kode }} — {{ $judul }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css'])
    </head>
    <body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 font-sans antialiased">
        <div class="w-full max-w-lg text-center">
            <p class="text-7xl font-bold tracking-tighter {{ $warna }}">{{ $kode }}</p>
            <h1 class="mt-3 text-2xl font-semibold text-gray-900">{{ $judul }}</h1>
            <p class="mt-2 text-sm text-gray-600">{{ $pesan ?? 'Maaf, terjadi kendala saat memproses permintaan Anda.' }}</p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ url()->previous(route('dashboard')) }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Kembali
                </a>
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Ke Dashboard
                </a>
            </div>

            <p class="mt-8 text-xs text-gray-400">
                Kode referensi: {{ substr((string) ($requestId ?? Str::uuid()), 0, 8) }}
            </p>
        </div>
    </body>
</html>
