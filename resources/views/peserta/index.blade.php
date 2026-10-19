@php
    $bolehUbah = auth()->user()?->isAdmin() ?? false;
@endphp

<x-app-layout title="Data Peserta" :subtitle="'Menampilkan '.$daftarPeserta->total().' peserta'">
    <x-slot:actions>
        @if ($bolehUbah)
            <a href="{{ route('peserta.terhapus') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Terhapus
            </a>

            <a href="{{ route('peserta.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Peserta
            </a>
        @endif
    </x-slot:actions>

    {{-- Filter --}}
    <form method="GET" action="{{ route('peserta.index') }}" class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="cari" class="mb-1.5 block text-sm font-medium text-gray-700">Cari</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute top-2.5 left-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="search"
                        name="cari"
                        id="cari"
                        value="{{ $filter['cari'] }}"
                        placeholder="Nama, email, atau NIK…"
                        class="block w-full rounded-lg border-gray-300 py-2 pl-10 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                </div>
            </div>

            <div class="sm:w-44">
                <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="block w-full rounded-lg border-gray-300 py-2 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected($filter['status'] === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected($filter['status'] === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <div class="sm:w-44">
                <label for="urut" class="mb-1.5 block text-sm font-medium text-gray-700">Urutkan</label>
                <select name="urut" id="urut" class="block w-full rounded-lg border-gray-300 py-2 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Terbaru</option>
                    <option value="nama_lengkap" @selected($filter['urut'] === 'nama_lengkap')>Nama A–Z</option>
                    <option value="tanggal_lahir" @selected($filter['urut'] === 'tanggal_lahir')>Tanggal lahir</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                    Terapkan
                </button>
                @if ($filter['cari'] || $filter['status'] || $filter['urut'])
                    <a href="{{ route('peserta.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        @if ($daftarPeserta->isEmpty())
            <x-empty-state
                judul="{{ $filter['cari'] || $filter['status'] ? 'Tidak ada peserta yang cocok' : 'Belum ada data peserta' }}"
                deskripsi="{{ $filter['cari'] || $filter['status'] ? 'Coba ubah kata kunci atau filter status.' : 'Tambahkan peserta pertama untuk memulai pendataan.' }}"
            />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">NIK</th>
                            <th scope="col" class="hidden px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 md:table-cell">Telepon</th>
                            <th scope="col" class="hidden px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 lg:table-cell">Usia</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($daftarPeserta as $p)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                            {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-gray-900">{{ $p->nama_lengkap }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $p->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $p->nik }}</td>
                                <td class="hidden px-5 py-3 text-sm text-gray-600 md:table-cell">{{ $p->no_telepon }}</td>
                                <td class="hidden px-5 py-3 text-sm text-gray-600 lg:table-cell">{{ $p->labelUsia() }} th</td>
                                <td class="px-5 py-3"><x-badge-status :status="$p->status" /></td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('peserta.show', $p) }}" class="rounded-md p-1.5 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900" title="Lihat detail" aria-label="Lihat detail {{ $p->nama_lengkap }}">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>

                                        @if ($bolehUbah)
                                            <a href="{{ route('peserta.edit', $p) }}" class="rounded-md p-1.5 text-indigo-600 transition hover:bg-indigo-50" title="Ubah" aria-label="Ubah {{ $p->nama_lengkap }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>

                                            <x-modal-hapus :peserta="$p" />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-5 py-3">
                {{ $daftarPeserta->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
