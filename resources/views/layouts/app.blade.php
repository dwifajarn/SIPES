<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' — '.config('app.name') : config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        x-data="{
            fontSize: localStorage.getItem('sipes_font_size') || 'normal',
            highContrast: localStorage.getItem('sipes_contrast') === 'true',
            setFont(size) {
                this.fontSize = size;
                localStorage.setItem('sipes_font_size', size);
            },
            toggleContrast() {
                this.highContrast = !this.highContrast;
                localStorage.setItem('sipes_contrast', this.highContrast);
            }
        }"
        :class="{
            'text-[15px]': fontSize === 'medium',
            'text-[17px]': fontSize === 'large',
            'contrast-125 saturate-150': highContrast
        }"
        class="font-sans antialiased bg-gray-100 selection:bg-indigo-500 selection:text-white"
    >
        <x-toast />

        <div x-data="{ sidebarTerbuka: false }" class="min-h-screen lg:flex">

            {{-- Overlay mobile --}}
            <div
                x-show="sidebarTerbuka"
                x-transition.opacity
                @click="sidebarTerbuka = false"
                class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
                aria-hidden="true"
            ></div>

            {{-- Sidebar --}}
            <aside
                x-show="true"
                :class="sidebarTerbuka ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="fixed inset-y-0 left-0 z-40 w-64 transform bg-gray-900 text-gray-300 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
                aria-label="Navigasi utama"
            >
                <div class="flex h-full flex-col">
                    <div class="flex h-16 items-center gap-2 border-b border-gray-800 px-6">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 font-bold text-white">
                            S
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ config('app.name') }}</p>
                            <p class="text-xs text-gray-500">Pendataan Peserta</p>
                        </div>
                    </div>

                    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                        <x-nav-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                            Dashboard
                        </x-nav-sidebar>

                        <x-nav-sidebar :href="route('peserta.index')" :active="request()->routeIs('peserta.*')" icon="users">
                            Data Peserta
                        </x-nav-sidebar>

                        @if (auth()->user()?->isAdmin())
                            <p class="px-3 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Administrasi
                            </p>

                            <x-nav-sidebar :href="route('pesan.index')" :active="request()->routeIs('pesan.*')" icon="bell" :badge="app(\App\Services\MessageService::class)::jumlahBelumDibaca()">
                                Pusat Pesan
                            </x-nav-sidebar>

                            <x-nav-sidebar :href="route('demo.index')" :active="request()->routeIs('demo.*')" icon="beaker">
                                Demo Pesan
                            </x-nav-sidebar>
                        @endif

                        <p class="px-3 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Akun
                        </p>

                        <x-nav-sidebar :href="route('profile.edit')" :active="request()->routeIs('profile.*')" icon="user">
                            Profil Saya
                        </x-nav-sidebar>
                    </nav>

                    <div class="border-t border-gray-800 p-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-400 transition hover:bg-gray-800 hover:text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4-4m-4 4l4 4m6-11h5a2 2 0 012 2v10a2 2 0 01-2 2h-5" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Konten --}}
            <div class="flex min-w-0 flex-1 flex-col">
                <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
                    <button
                        type="button"
                        @click="sidebarTerbuka = true"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden"
                        aria-label="Buka menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        <h1 class="truncate text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                        @isset($subtitle)
                            <p class="truncate text-xs text-gray-500">{{ $subtitle }}</p>
                        @endisset
                    </div>

                    @isset($actions)
                        <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
                    @endisset

                    {{-- Tombol Pengaturan Aksesibilitas IMK --}}
                    <div x-data="{ bukaAksesibilitas: false }" class="relative">
                        <button
                            type="button"
                            @click="bukaAksesibilitas = !bukaAksesibilitas"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-xs hover:bg-gray-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            title="Pengaturan Aksesibilitas Tampilan"
                            aria-label="Pengaturan Aksesibilitas Tampilan"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                        </button>

                        <div
                            x-show="bukaAksesibilitas"
                            @click.outside="bukaAksesibilitas = false"
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-64 rounded-xl border border-gray-200 bg-white p-4 shadow-xl z-50"
                        >
                            <div class="border-b border-gray-100 pb-2 mb-3">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-800">Aksesibilitas (IMK)</p>
                                <p class="text-[11px] text-gray-500">Sesuaikan kenyamanan visual Anda.</p>
                            </div>

                            {{-- Ukuran Font --}}
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Ukuran Teks</label>
                                <div class="grid grid-cols-3 gap-1 bg-gray-100 p-1 rounded-lg text-xs font-medium text-center">
                                    <button
                                        type="button"
                                        @click="setFont('normal')"
                                        :class="fontSize === 'normal' ? 'bg-white font-bold text-indigo-600 shadow-xs' : 'text-gray-600'"
                                        class="py-1 rounded transition"
                                    >
                                        A (100%)
                                    </button>
                                    <button
                                        type="button"
                                        @click="setFont('medium')"
                                        :class="fontSize === 'medium' ? 'bg-white font-bold text-indigo-600 shadow-xs' : 'text-gray-600'"
                                        class="py-1 rounded transition text-sm"
                                    >
                                        A+ (115%)
                                    </button>
                                    <button
                                        type="button"
                                        @click="setFont('large')"
                                        :class="fontSize === 'large' ? 'bg-white font-bold text-indigo-600 shadow-xs' : 'text-gray-600'"
                                        class="py-1 rounded transition text-base"
                                    >
                                        A++ (125%)
                                    </button>
                                </div>
                            </div>

                            {{-- High Contrast Toggle --}}
                            <div>
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-700">Kontras Tinggi</span>
                                    <input
                                        type="checkbox"
                                        @change="toggleContrast()"
                                        :checked="highContrast"
                                        class="rounded border-gray-300 text-indigo-600 shadow-xs focus:ring-indigo-500"
                                    >
                                </label>
                                <p class="text-[10px] text-gray-500 mt-0.5">Meningkatkan saturasi dan kejelasan batas elemen.</p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden items-center gap-2 rounded-full bg-gray-50 py-1 pr-3 pl-1 sm:flex">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500">{{ auth()->user()->isAdmin() ? 'Admin' : 'Pengguna' }}</p>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6">
                    {{-- Pesan sistem global --}}
                    @if (session('pesan'))
                        <x-alert
                            :type="session('pesan.type')"
                            :title="session('pesan.title')"
                            class="mb-5"
                        >
                            {{ session('pesan.body') }}
                        </x-alert>
                    @endif

                    @if (session('status'))
                        <x-alert type="success" class="mb-5">{{ session('status') }}</x-alert>
                    @endif

                    {{-- Ringkasan kesalahan validasi --}}
                    @if ($errors->any())
                        <x-alert type="error" :title="'Periksa kembali isian Anda ('.$errors->count().' kesalahan)'" :dismissible="false" class="mb-5">
                            <ul class="mt-1 list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
