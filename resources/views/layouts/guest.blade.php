<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPES') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 p-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-md text-xl">
                    S
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-gray-900">{{ config('app.name', 'SIPES') }}</h1>
                    <p class="text-xs text-gray-500">Sistem Pendataan Peserta</p>
                </div>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-lg border border-gray-200/80 rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center text-xs text-gray-500">
                Tugas Interaksi Manusia & Komputer (IMK) &copy; {{ date('Y') }}
            </p>
        </div>
    </body>
</html>
