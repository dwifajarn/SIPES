@props(['peserta'])

<div x-data="{ terbuka: false }" class="inline-block">
    <button
        type="button"
        @click="terbuka = true"
        class="rounded-md p-1.5 text-red-600 transition hover:bg-red-50"
        title="Hapus"
        aria-label="Hapus {{ $peserta->nama_lengkap }}"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    <div
        x-show="terbuka"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 p-4"
        @keydown.escape.window="terbuka = false"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judul-hapus-{{ $peserta->id }}"
    >
        <div x-show="terbuka" x-transition class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @click.outside="terbuka = false">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 id="judul-hapus-{{ $peserta->id }}" class="text-base font-semibold text-gray-900">Hapus peserta ini?</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        <span class="font-medium text-gray-900">{{ $peserta->nama_lengkap }}</span> akan dipindahkan ke daftar terhapus.
                        Data masih dapat dipulihkan oleh admin.
                    </p>
                </div>
            </div>

            <div class="mt-5 flex justify-end gap-2">
                <button type="button" @click="terbuka = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Batal
                </button>

                <form method="POST" action="{{ route('peserta.destroy', $peserta) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Ya, hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
