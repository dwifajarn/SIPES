<x-app-layout title="Pusat Pesan" subtitle="Riwayat seluruh pesan yang dikeluarkan sistem">
    <x-slot:actions>
        @if ($jumlahBelumDibaca > 0)
            <form method="POST" action="{{ route('pesan.dibaca-semua') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </x-slot:actions>

    @if ($jumlahBelumDibaca > 0)
        <x-alert type="info" class="mb-4" :dismissible="false">
            Anda memiliki <strong>{{ $jumlahBelumDibaca }}</strong> pesan yang belum dibaca.
        </x-alert>
    @endif

    {{-- Filter --}}
    <div class="mb-4 flex flex-wrap gap-2">
        @php
            $tab = ['' => 'Semua'] + ['success' => 'Sukses', 'info' => 'Info', 'warning' => 'Peringatan', 'error' => 'Error'];
        @endphp

        @foreach ($tab as $nilai => $label)
            <a
                href="{{ route('pesan.index', $nilai ? ['tipe' => $nilai] : []) }}"
                @class([
                    'rounded-full px-3.5 py-1.5 text-sm font-medium transition',
                    'bg-indigo-600 text-white shadow-sm' => $filterTipe === $nilai,
                    'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' => $filterTipe !== $nilai,
                ])
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        @if ($daftarPesan->isEmpty())
            <x-empty-state judul="Belum ada pesan" deskripsi="Pesan sistem akan muncul di sini setelah ada aktivitas." />
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($daftarPesan as $pesan)
                    <li @class([
                        'flex items-start gap-3 px-5 py-4 transition hover:bg-gray-50',
                        'bg-indigo-50/40' => ! $pesan->is_read,
                    ])>
                        <x-dot-tipe :tipe="$pesan->type" class="mt-2" />

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p @class([
                                    'text-sm text-gray-900',
                                    'font-semibold' => ! $pesan->is_read,
                                    'font-medium' => $pesan->is_read,
                                ])>{{ $pesan->title }}</p>

                                <x-badge-tipe :tipe="$pesan->type" />

                                @if ($pesan->code)
                                    <span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-600">{{ $pesan->code }}</span>
                                @endif

                                @if (! $pesan->is_read)
                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-semibold text-indigo-700">Baru</span>
                                @endif
                            </div>

                            <p class="mt-1 text-sm text-gray-600">{{ $pesan->body }}</p>

                            <p class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                                <span>{{ $pesan->created_at->translatedFormat('d M Y, H:i') }}</span>
                                @if ($pesan->user)
                                    <span>oleh {{ $pesan->user->name }}</span>
                                @endif
                                @if ($pesan->url)
                                    <span class="truncate font-mono">{{ Str::limit($pesan->url, 60) }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="flex shrink-0 items-center gap-1">
                            @if (! $pesan->is_read)
                                <form method="POST" action="{{ route('pesan.dibaca', $pesan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-md p-1.5 text-indigo-600 transition hover:bg-indigo-50" title="Tandai dibaca" aria-label="Tandai pesan sudah dibaca">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('pesan.destroy', $pesan) }}" onsubmit="return confirm('Hapus pesan ini dari riwayat?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md p-1.5 text-red-600 transition hover:bg-red-50" title="Hapus" aria-label="Hapus pesan">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-gray-100 px-5 py-3">
                {{ $daftarPesan->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
