<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPES') }} — Sistem Pendataan Peserta & Feedback Interaksi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">
        {{-- Navbar --}}
        <header x-data="{ mobileMenu: false }" class="sticky top-0 z-50 backdrop-blur-md bg-white/90 border-b border-slate-200/80 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    {{-- Logo --}}
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 font-bold text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition transform">
                            S
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold tracking-tight text-slate-900 leading-none">{{ config('app.name', 'SIPES') }}</span>
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold text-indigo-700 border border-indigo-200/60">
                                    IMK Edition
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium">Sistem Pendataan Peserta</p>
                        </div>
                    </a>

                    {{-- Desktop Navigation --}}
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                        <a href="#fitur" class="hover:text-indigo-600 transition">Fitur Utama</a>
                        <a href="#prinsip" class="hover:text-indigo-600 transition">Prinsip IMK</a>
                        <a href="#katalog" class="hover:text-indigo-600 transition">Katalog Pesan</a>
                        <a href="#alur" class="hover:text-indigo-600 transition">Alur Kerja</a>
                    </nav>

                    {{-- Auth Actions --}}
                    <div class="hidden sm:flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 shadow-indigo-600/20 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span>Buka Dashboard</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 shadow-indigo-600/20 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    </div>

                    {{-- Mobile menu button --}}
                    <div class="flex sm:hidden">
                        <button @click="mobileMenu = !mobileMenu" type="button" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none" aria-label="Buka menu">
                            <svg x-show="!mobileMenu" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileMenu" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile menu dropdown --}}
            <div x-show="mobileMenu" x-cloak x-transition class="sm:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-5 space-y-3">
                <a @click="mobileMenu = false" href="#fitur" class="block py-2 text-base font-medium text-slate-700 hover:text-indigo-600">Fitur Utama</a>
                <a @click="mobileMenu = false" href="#prinsip" class="block py-2 text-base font-medium text-slate-700 hover:text-indigo-600">Prinsip IMK</a>
                <a @click="mobileMenu = false" href="#katalog" class="block py-2 text-base font-medium text-slate-700 hover:text-indigo-600">Katalog Pesan</a>
                <a @click="mobileMenu = false" href="#alur" class="block py-2 text-base font-medium text-slate-700 hover:text-indigo-600">Alur Kerja</a>
                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 rounded-lg bg-indigo-600 text-white font-semibold text-sm">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-lg border border-slate-300 font-semibold text-sm text-slate-700">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-lg bg-indigo-600 text-white font-semibold text-sm">Daftar Akun</a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <main>
            {{-- Hero Section --}}
            <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
                {{-- Decorative background gradient blobs --}}
                <div class="pointer-events-none absolute -top-40 left-1/2 -z-10 -translate-x-1/2 transform-gpu blur-3xl sm:top-[-20rem]">
                    <div class="aspect-[1155/678] w-[72.1875rem] bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 opacity-70"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto">
                        <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-200/80 mb-6 shadow-xs">
                            <span class="flex h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
                            Tugas Mata Kuliah Interaksi Manusia & Komputer
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                            Pendataan Peserta yang <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 bg-clip-text text-transparent">Jelas, Valid,</span> & Manusiawi.
                        </h1>

                        <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed">
                            Mengubah frustrasi kesalahan input formulir menjadi panduan yang solutif. Setiap kekeliruan dijelaskan dalam Bahasa Indonesia yang lugas, terhubung langsung di bawah kolom isian, dan tercatat otomatis di Pusat Pesan sistem.
                        </p>

                        <div class="mt-8 sm:mt-10 flex flex-wrap items-center justify-center gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-indigo-600/25 hover:bg-indigo-700 hover:shadow-indigo-600/35 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span>Buka Dashboard Admin</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-indigo-600/25 hover:bg-indigo-700 hover:shadow-indigo-600/35 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span>Masuk ke Aplikasi</span>
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-base font-semibold text-slate-700 shadow-xs hover:bg-slate-50 hover:text-slate-900 transition">
                                        Daftar Akun Baru
                                    </a>
                                @endif
                            @endauth
                            <a href="#katalog" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-transparent px-4 py-3.5 text-base font-medium text-slate-600 hover:text-indigo-600 transition">
                                Lihat Contoh Pesan &darr;
                            </a>
                        </div>

                        {{-- Akun Cepat Info --}}
                        <div class="mt-8 p-3 bg-indigo-50/70 border border-indigo-100 rounded-xl inline-flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs text-indigo-900">
                            <span class="font-bold flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                Akun Demo Pengujian:
                            </span>
                            <span>Admin: <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-indigo-200">admin@sipes.test</code></span>
                            <span>User: <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-indigo-200">user@sipes.test</code></span>
                            <span>Password: <code class="font-mono bg-white px-1.5 py-0.5 rounded border border-indigo-200">password</code></span>
                        </div>
                    </div>

                    {{-- Live Interactive Showcase Card --}}
                    <div x-data="{ tab: 'error' }" class="mt-14 max-w-4xl mx-auto rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-200/50 overflow-hidden">
                        {{-- Mockup Topbar --}}
                        <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-4 py-3 sm:px-6">
                            <div class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-red-400"></span>
                                <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                <span class="h-3 w-3 rounded-full bg-green-400"></span>
                                <span class="ml-2 text-xs font-mono text-slate-400">preview.sipes.test/peserta/create</span>
                            </div>

                            {{-- Tab Switches --}}
                            <div class="flex items-center bg-slate-200/80 p-0.5 rounded-lg text-xs font-semibold">
                                <button
                                    type="button"
                                    @click="tab = 'error'"
                                    :class="tab === 'error' ? 'bg-white text-red-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1 rounded-md transition"
                                >
                                    Skenario Input Salah
                                </button>
                                <button
                                    type="button"
                                    @click="tab = 'valid'"
                                    :class="tab === 'valid' ? 'bg-white text-green-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1 rounded-md transition"
                                >
                                    Skenario Input Benar
                                </button>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 sm:p-8">
                            {{-- Tab: Error State --}}
                            <div x-show="tab === 'error'" x-cloak class="space-y-5">
                                {{-- Top Summary Alert --}}
                                <div class="flex items-start gap-3 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-900">
                                    <svg class="h-5 w-5 shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-red-900">Periksa kembali isian Anda (3 kesalahan)</p>
                                        <ul class="mt-1 list-disc list-inside space-y-0.5 text-xs text-red-800">
                                            <li>Nama lengkap hanya boleh berisi huruf dan spasi.</li>
                                            <li>Format email tidak valid.</li>
                                            <li>NIK harus terdiri dari 16 digit angka.</li>
                                        </ul>
                                    </div>
                                </div>

                                {{-- Sample Form Fields with Errors --}}
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" value="Budi123" class="block w-full rounded-lg border-red-400 bg-red-50/50 py-2 px-3 text-sm text-red-900 font-medium" readonly>
                                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 font-medium">
                                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            Nama lengkap hanya boleh berisi huruf dan spasi.
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                                        <input type="text" value="budi@@mail" class="block w-full rounded-lg border-red-400 bg-red-50/50 py-2 px-3 text-sm text-red-900 font-medium" readonly>
                                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600 font-medium">
                                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            Format email tidak valid.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab: Valid State --}}
                            <div x-show="tab === 'valid'" x-cloak class="space-y-5">
                                {{-- Success Banner --}}
                                <div class="flex items-start gap-3 rounded-lg border-l-4 border-green-500 bg-green-50 p-4 text-green-900">
                                    <svg class="h-5 w-5 shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    <div>
                                        <p class="text-sm font-semibold text-green-900">Data peserta berhasil disimpan</p>
                                        <p class="text-xs text-green-800 mt-0.5">Peserta "Ahmad Fauzi" telah terdaftar dalam sistem dan tercatat di audit log.</p>
                                    </div>
                                </div>

                                {{-- Valid Fields --}}
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                        <div class="relative">
                                            <input type="text" value="Ahmad Fauzi Rahman" class="block w-full rounded-lg border-green-400 bg-white py-2 px-3 text-sm text-slate-900 font-medium" readonly>
                                            <svg class="absolute right-3 top-2.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">Terisi sesuai dokumen KTP.</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                                        <div class="relative">
                                            <input type="text" value="ahmad.fauzi@contoh.com" class="block w-full rounded-lg border-green-400 bg-white py-2 px-3 text-sm text-slate-900 font-medium" readonly>
                                            <svg class="absolute right-3 top-2.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500">Format RFC valid & belum pernah dipakai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Angka & Pencapaian --}}
            <section class="border-y border-slate-200 bg-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 gap-6 md:grid-cols-4 text-center">
                        <div>
                            <p class="text-3xl sm:text-4xl font-extrabold text-indigo-600">100%</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-slate-600">Bahasa Indonesia Manusiawi</p>
                        </div>
                        <div>
                            <p class="text-3xl sm:text-4xl font-extrabold text-indigo-600">3 Lapis</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-slate-600">Umpan Balik Pesan Sistem</p>
                        </div>
                        <div>
                            <p class="text-3xl sm:text-4xl font-extrabold text-indigo-600">69 Unit</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-slate-600">Feature Test Otomatis (100% Pass)</p>
                        </div>
                        <div>
                            <p class="text-3xl sm:text-4xl font-extrabold text-indigo-600">0 Data Loss</p>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-slate-600">Didukung Soft Delete & Restore</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Fitur Unggulan Grid --}}
            <section id="fitur" class="py-20 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Fitur Dirancang untuk IMK</h2>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            Dibangun Bukan Sekadar Jalan, Tapi Siap Produksi.
                        </p>
                        <p class="mt-4 text-slate-600 text-sm sm:text-base">
                            Setiap modul disesuaikan dengan prinsip interaksi manusia dan komputer agar pengguna tidak merasa bingung saat menghadapi kesalahan.
                        </p>
                    </div>

                    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {{-- Card 1 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Umpan Balik Validasi Tepat Sasaran</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Pesan kesalahan tampil tepat di bawah bidang yang bermasalah dengan border merah menyala, disertai ringkasan di atas formulir.
                            </p>
                        </div>

                        {{-- Card 2 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Pusat Pesan & Audit Trail</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Setiap aksi simpan, hapus, dan validasi gagal dicatat ke database lengkap dengan kode status HTTP dan tautan halaman asal.
                            </p>
                        </div>

                        {{-- Card 3 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Hak Akses Berlapis (Role Policy)</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Pemisahan kewenangan ketat antara Admin (mutasi penuh) dan User (baca saja), diverifikasi di level middleware dan policy.
                            </p>
                        </div>

                        {{-- Card 4 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Pemulihan Data (Soft Delete)</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Mencegah ketakutan kehilangan data. Peserta yang terhapus masuk ke daftar terhapus dan dapat dipulihkan kembali kapan saja.
                            </p>
                        </div>

                        {{-- Card 5 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Isian Lama Tidak Hilang (Old Input)</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Pengguna tidak perlu mengetik ulang seluruh formulir saat satu field salah. Seluruh isian dipertahankan secara otomatis.
                            </p>
                        </div>

                        {{-- Card 6 --}}
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-md transition">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Aksesibel & Responsif Penuh</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                                Dilengkapi atribut ARIA (`aria-invalid`, `aria-describedby`) untuk pembaca layar, kontras teks tinggi, dan layout fleksibel di semua layar.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Prinsip IMK Section --}}
            <section id="prinsip" class="py-20 bg-white border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Teori ke Praktik</span>
                            <h2 class="mt-2 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                                Penerapan Prinsip Heuristik Nielsen
                            </h2>
                            <p class="mt-4 text-slate-600 text-sm sm:text-base leading-relaxed">
                                Antarmuka SIPES secara khusus mengimplementasikan 4 pilar heuristik kegunaan sistem untuk menciptakan pengalaman pengguna yang nyaman dan bebas dari kepanikan teknologi.
                            </p>

                            <div class="mt-8 space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold mt-0.5">1</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Visibility of System Status</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Umpan balik seketika saat data tersimpan, diperbarui, atau dihapus.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold mt-0.5">2</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Match Between System & Real World</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Bahasa Indonesia sehari-hari, bebas dari istilah server atau class PHP.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold mt-0.5">3</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Error Prevention & Recovery</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Modal konfirmasi hapus dan penjelasan konkret tentang cara memperbaiki isian salah.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-xs font-bold mt-0.5">4</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Recognition Rather Than Recall</p>
                                        <p class="text-xs text-slate-600 mt-0.5">Helper text dan format contoh (placeholder) tersedia sebelum pengguna mulai mengetik.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Code & Rule Comparison Visual --}}
                        <div class="lg:col-span-7 bg-slate-900 text-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl border border-slate-800">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-800 text-xs text-slate-400 font-mono">
                                <span>app/Http/Requests/StorePesertaRequest.php</span>
                                <span class="bg-indigo-950 text-indigo-400 border border-indigo-800/80 px-2 py-0.5 rounded">Custom Messages</span>
                            </div>

                            <pre class="mt-4 font-mono text-xs sm:text-sm text-indigo-300 overflow-x-auto leading-relaxed"><code><span class="text-purple-400">public function</span> <span class="text-blue-400">messages</span>(): <span class="text-purple-400">array</span>
{
    <span class="text-purple-400">return</span> [
        <span class="text-emerald-300">'nama_lengkap.regex'</span> => <span class="text-amber-200">'Nama lengkap hanya boleh berisi huruf dan spasi.'</span>,
        <span class="text-emerald-300">'email.unique'</span>       => <span class="text-amber-200">'Email sudah terdaftar dalam sistem.'</span>,
        <span class="text-emerald-300">'nik.digits'</span>         => <span class="text-amber-200">'NIK harus terdiri dari 16 digit angka.'</span>,
        <span class="text-emerald-300">'tanggal_lahir.before'</span> => <span class="text-amber-200">'Usia minimal 17 tahun.'</span>,
        <span class="text-emerald-300">'no_telepon.regex'</span>    => <span class="text-amber-200">'Nomor telepon hanya boleh berisi angka (10–15 digit).'</span>,
    ];
}</code></pre>

                            <div class="mt-6 pt-5 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                                <span>✓ Ditulis khusus untuk tugas IMK</span>
                                <span>✓ Terhubung langsung ke FormRequest</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Katalog 4 Tipe Pesan (#katalog) --}}
            <section id="katalog" class="py-20 bg-slate-50 border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-14">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Sistem Desain Umpan Balik</h2>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            Empat Tipe Pesan Standar
                        </p>
                        <p class="mt-4 text-slate-600 text-sm">
                            Konsistensi warna, ikon, dan semantik ARIA yang diterapkan pada seluruh penjuru aplikasi.
                        </p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        {{-- Sukses --}}
                        <div class="rounded-xl border border-green-200 bg-white p-5 shadow-xs">
                            <div class="flex items-start gap-3">
                                <span class="p-2 rounded-lg bg-green-100 text-green-700">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">Pesan Sukses (Success)</h3>
                                        <span class="font-mono text-[10px] bg-green-50 text-green-700 border border-green-200 px-1.5 py-0.5 rounded">green-600</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">Digunakan ketika data peserta berhasil ditambahkan, diubah, atau dipulihkan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="rounded-xl border border-blue-200 bg-white p-5 shadow-xs">
                            <div class="flex items-start gap-3">
                                <span class="p-2 rounded-lg bg-blue-100 text-blue-700">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">Pesan Informasi (Info)</h3>
                                        <span class="font-mono text-[10px] bg-blue-50 text-blue-700 border border-blue-200 px-1.5 py-0.5 rounded">blue-600</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">Digunakan untuk pemberitahuan netral, jumlah pesan belum dibaca, atau jadwal pemeliharaan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Peringatan --}}
                        <div class="rounded-xl border border-amber-200 bg-white p-5 shadow-xs">
                            <div class="flex items-start gap-3">
                                <span class="p-2 rounded-lg bg-amber-100 text-amber-700">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">Pesan Peringatan (Warning)</h3>
                                        <span class="font-mono text-[10px] bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded">amber-500</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">Digunakan saat data dipindahkan ke daftar terhapus atau kuota data mendekati batas.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Error --}}
                        <div class="rounded-xl border border-red-200 bg-white p-5 shadow-xs">
                            <div class="flex items-start gap-3">
                                <span class="p-2 rounded-lg bg-red-100 text-red-700">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">Pesan Kesalahan (Error)</h3>
                                        <span class="font-mono text-[10px] bg-red-50 text-red-700 border border-red-200 px-1.5 py-0.5 rounded">red-600</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">Digunakan ketika aturan validasi dilanggar atau sistem mengalami kendala (422, 500).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Alur Kerja (#alur) --}}
            <section id="alur" class="py-20 bg-white border-t border-slate-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-14">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-600">Alur Sederhana</h2>
                        <p class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            Cara Menguji Aplikasi SIPES
                        </p>
                    </div>

                    <div class="grid gap-8 md:grid-cols-3 relative">
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold mb-4">1</div>
                            <h3 class="text-base font-bold text-slate-900">Masuk sebagai Admin</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600">
                                Gunakan akun <code class="bg-white px-1 py-0.5 rounded border border-slate-200 font-mono text-[11px]">admin@sipes.test</code> untuk mendapatkan kendali penuh atas data dan melihat log pesan sistem.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold mb-4">2</div>
                            <h3 class="text-base font-bold text-slate-900">Uji Validasi Form</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600">
                                Buka menu <strong>Tambah Peserta</strong>, sengaja kosongkan atau masukkan format salah (misal: NIK 15 digit, email salah) untuk melihat pesan error real-time.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-bold mb-4">3</div>
                            <h3 class="text-base font-bold text-slate-900">Periksa Audit di Pusat Pesan</h3>
                            <p class="mt-2 text-xs sm:text-sm text-slate-600">
                                Buka menu <strong>Pusat Pesan</strong> untuk memverifikasi bahwa seluruh kesalahan input tadi telah dicatat dengan kode 422 untuk kebutuhan audit.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA Banner --}}
            <section class="py-16 bg-slate-900 text-white relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                        Siap Mengevaluasi Sistem Pendataan Peserta?
                    </h2>
                    <p class="mt-4 text-slate-400 max-w-xl mx-auto text-sm sm:text-base">
                        Aplikasi telah terpasang dengan database MySQL lokal dan 69 test otomatis teruji.
                    </p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg hover:bg-indigo-500 transition">
                                Buka Dashboard Aplikasi &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg hover:bg-indigo-500 transition">
                                Masuk ke Sistem &rarr;
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-6 py-3.5 text-base font-semibold text-slate-200 hover:bg-slate-700 transition">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-slate-200 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div class="flex items-center gap-2">
                    <div class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 font-bold text-white text-[11px]">S</div>
                    <span class="font-bold text-slate-800">{{ config('app.name', 'SIPES') }}</span>
                    <span>&mdash; Sistem Pendataan Peserta & Feedback Interaksi</span>
                </div>
                <div>
                    Tugas Besar IMK &bull; Semester 7 &bull; Laravel {{ app()->version() }}
                </div>
            </div>
        </footer>
    </body>
</html>
