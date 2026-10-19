<x-app-layout title="Peserta Terhapus" subtitle="Data yang dapat dipulihkan kembali">
    <x-slot:actions>
        <a href="{{ route('peserta.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            Kembali ke Daftar
        </a>
    </x-slot:actions>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        @if ($daftarPeserta->isEmpty())
            <x-empty-state judul="Tidak ada data terhapus" deskripsi="Semua data peserta masih utuh di daftar utama." />
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                            <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Dihapus</th>
                            <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($daftarPeserta as $p)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $p->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->nik }}</p>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $p->email }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $p->deleted_at->diffForHumans() }}</td>
                                <td class="px-5 py-3 text-right">
                                    <form method="POST" action="{{ route('peserta.restore', $p->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 transition hover:bg-green-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Pulihkan
                                        </button>
                                    </form>
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
