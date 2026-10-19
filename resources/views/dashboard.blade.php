@php
    $kartu = [
        ['label' => 'Total Peserta', 'nilai' => $statistik['total'], 'latar' => 'bg-indigo-50', 'teks' => 'text-indigo-600', 'ikon' => 'users'],
        ['label' => 'Aktif', 'nilai' => $statistik['aktif'], 'latar' => 'bg-green-50', 'teks' => 'text-green-600', 'ikon' => 'check'],
        ['label' => 'Nonaktif', 'nilai' => $statistik['nonaktif'], 'latar' => 'bg-gray-100', 'teks' => 'text-gray-500', 'ikon' => 'pause'],
        ['label' => 'Error Hari Ini', 'nilai' => $statistik['errorHariIni'], 'latar' => 'bg-red-50', 'teks' => 'text-red-600', 'ikon' => 'alert'],
    ];
@endphp

<x-app-layout title="Dashboard" subtitle="Ringkasan data peserta dan pesan sistem">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kartu as $item)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ $item['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($item['nilai'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg {{ $item['latar'] }} {{ $item['teks'] }}">
                        @if ($item['ikon'] === 'users')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        @elseif ($item['ikon'] === 'check')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @elseif ($item['ikon'] === 'pause')
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Peserta Terbaru</h2>
                <a href="{{ route('peserta.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
            </div>

            @if ($pesertaTerbaru->isEmpty())
                <x-empty-state judul="Belum ada data peserta" deskripsi="Tambahkan peserta pertama untuk memulai." />
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($pesertaTerbaru as $p)
                        <li class="flex items-center gap-3 px-5 py-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900">{{ $p->nama_lengkap }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $p->email }}</p>
                            </div>
                            <x-badge-status :status="$p->status" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Pesan Sistem Terbaru</h2>
                @if (auth()->user()?->isAdmin())
                    <a href="{{ route('pesan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat semua →</a>
                @endif
            </div>

            @if ($pesanTerbaru->isEmpty())
                <x-empty-state judul="Belum ada pesan" deskripsi="Pesan akan muncul setelah ada aktivitas pada sistem." />
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($pesanTerbaru as $pesan)
                        <li class="flex items-start gap-3 px-5 py-3">
                            <x-dot-tipe :tipe="$pesan->type" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900">{{ $pesan->title }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $pesan->body }}</p>
                            </div>
                            <time class="shrink-0 text-xs text-gray-400">{{ $pesan->created_at->diffForHumans() }}</time>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
