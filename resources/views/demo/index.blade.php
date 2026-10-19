<x-app-layout title="Demo Pesan Sistem" subtitle="Katalog tampilan pesan untuk dokumentasi laporan IMK">
    <div class="space-y-6">

        {{-- 1. Empat tipe pesan --}}
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">1. Empat Tipe Pesan</h2>
                <p class="mt-0.5 text-xs text-gray-500">Setiap tipe punya warna, ikon, dan makna yang konsisten di seluruh aplikasi.</p>
            </div>

            <div class="space-y-3 p-6">
                <x-alert type="success" title="Data berhasil disimpan">
                    Peserta "Budi Santoso" telah ditambahkan ke sistem.
                </x-alert>

                <x-alert type="info" title="Pembaruan sistem">
                    Aplikasi akan menjalani pemeliharaan pada pukul 20.00 WIB.
                </x-alert>

                <x-alert type="warning" title="Kuota hampir penuh">
                    Anda telah memakai 90% dari kapasitas penyimpanan dokumen.
                </x-alert>

                <x-alert type="error" title="Gagal memproses data">
                    Terjadi kesalahan saat menyimpan data. Silakan coba lagi beberapa saat.
                </x-alert>
            </div>

            <div class="flex flex-wrap gap-2 border-t border-gray-100 bg-gray-50 px-6 py-4">
                @foreach (['success' => 'Tampilkan Sukses', 'info' => 'Tampilkan Info', 'warning' => 'Tampilkan Peringatan', 'error' => 'Tampilkan Error'] as $tipe => $label)
                    <a href="{{ route('demo.contoh', $tipe) }}" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- 2. Pesan validasi form --}}
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">2. Pesan Validasi pada Form</h2>
                <p class="mt-0.5 text-xs text-gray-500">Pesan tampil di bawah field yang salah, bukan hanya di atas halaman.</p>
            </div>

            <div class="grid gap-5 p-6 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Contoh field valid</label>
                    <input type="text" value="Budi Santoso" class="block w-full rounded-lg border-gray-300 shadow-sm sm:text-sm" readonly>
                    <p class="mt-1.5 text-xs text-green-600">Terisi dengan benar.</p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Contoh field error</label>
                    <input type="text" value="budi@@mail" aria-invalid="true" class="block w-full rounded-lg border-red-400 bg-red-50 text-red-900 shadow-sm sm:text-sm" readonly>
                    <ul class="mt-1.5 space-y-0.5 text-sm text-red-600">
                        <li>Format email tidak valid.</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                <a href="{{ route('peserta.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Coba Form Peserta (kosongkan isian)
                </a>
            </div>
        </section>

        {{-- 3. Halaman error --}}
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">3. Halaman Error Sistem</h2>
                <p class="mt-0.5 text-xs text-gray-500">Kesalahan sistem ditampilkan sebagai halaman ramah, bukan kode teknis.</p>
            </div>

            <div class="grid gap-3 p-6 sm:grid-cols-3">
                <a href="{{ route('demo.404') }}" class="rounded-lg border border-gray-300 p-4 text-center transition hover:border-indigo-400 hover:bg-indigo-50">
                    <p class="text-2xl font-bold text-gray-900">404</p>
                    <p class="mt-1 text-sm text-gray-600">Data tidak ditemukan</p>
                </a>

                <a href="{{ route('demo.403') }}" class="rounded-lg border border-gray-300 p-4 text-center transition hover:border-amber-400 hover:bg-amber-50">
                    <p class="text-2xl font-bold text-gray-900">403</p>
                    <p class="mt-1 text-sm text-gray-600">Akses ditolak</p>
                </a>

                <a href="{{ route('demo.500') }}" class="rounded-lg border border-gray-300 p-4 text-center transition hover:border-red-400 hover:bg-red-50">
                    <p class="text-2xl font-bold text-gray-900">500</p>
                    <p class="mt-1 text-sm text-gray-600">Kesalahan server</p>
                </a>
            </div>
        </section>

        {{-- 4. Spesifikasi warna --}}
        <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">4. Spesifikasi Warna</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Warna</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kontras</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Penggunaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-6 py-3"><x-badge-tipe tipe="success" /></td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">green-600</td>
                            <td class="px-6 py-3 text-gray-600">4.6:1 (AA)</td>
                            <td class="px-6 py-3 text-gray-600">Data tersimpan atau terhapus</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3"><x-badge-tipe tipe="info" /></td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">blue-600</td>
                            <td class="px-6 py-3 text-gray-600">5.2:1 (AA)</td>
                            <td class="px-6 py-3 text-gray-600">Pemberitahuan netral</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3"><x-badge-tipe tipe="warning" /></td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">amber-500</td>
                            <td class="px-6 py-3 text-gray-600">3.1:1 (grafis)</td>
                            <td class="px-6 py-3 text-gray-600">Peringatan, data belum lengkap</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3"><x-badge-tipe tipe="error" /></td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-600">red-600</td>
                            <td class="px-6 py-3 text-gray-600">4.5:1 (AA)</td>
                            <td class="px-6 py-3 text-gray-600">Validasi gagal, kegagalan sistem</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
