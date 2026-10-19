@php
    $peserta ??= null;
    $sedangEdit = $peserta !== null;
    $judulTombol = $sedangEdit ? 'Simpan Perubahan' : 'Simpan Data';
@endphp

<x-app-layout
    :title="$sedangEdit ? 'Ubah Peserta' : 'Tambah Peserta'"
    :subtitle="$sedangEdit ? 'Perbarui data '.$peserta->nama_lengkap : 'Lengkapi seluruh isian bertanda *'"
>
    <x-slot:actions>
        <a href="{{ route('peserta.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-slot:actions>

    <form
        method="POST"
        action="{{ $sedangEdit ? route('peserta.update', $peserta) : route('peserta.store') }}"
        novalidate
        x-data="formPesertaRealtime()"
        class="max-w-3xl"
    >
        @csrf
        @if ($sedangEdit)
            @method('PUT')
        @endif

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-6 py-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900">Data Pribadi</h2>
                    <p class="mt-0.5 text-xs text-gray-500">Pastikan data sesuai dokumen identitas resmi.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200/80">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Validasi Real-time Aktif
                    </span>
                </div>
            </div>

            {{-- Ringkasan Real-time Error Klien jika sudah ada field yang diisi keliru --}}
            <template x-if="totalClientErrors() > 0">
                <div class="mx-6 mt-6 rounded-lg border-l-4 border-amber-500 bg-amber-50 p-3.5 text-amber-900 text-xs">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg class="h-4 w-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Pemberitahuan Pencegahan Kesalahan (IMK):</span>
                    </div>
                    <p class="mt-1 text-amber-800">
                        Terdapat <span class="font-bold" x-text="totalClientErrors()"></span> isian yang belum memenuhi format. Perbaiki kolom bertanda merah sebelum menyimpan.
                    </p>
                </div>
            </template>

            <div class="grid gap-5 p-6 sm:grid-cols-2">
                <x-form.input
                    name="nama_lengkap"
                    label="Nama Lengkap"
                    :value="$peserta?->nama_lengkap"
                    placeholder="Contoh: Budi Santoso"
                    helper="Minimal 3 karakter, hanya huruf dan spasi."
                    maxlength="100"
                    required
                    autofocus
                    :realtime="true"
                    x-model="form.nama_lengkap"
                    @input.debounce.300ms="validate('nama_lengkap')"
                    @blur="touch('nama_lengkap')"
                    class="sm:col-span-2"
                />

                <x-form.input
                    name="email"
                    type="email"
                    label="Email"
                    :value="$peserta?->email"
                    placeholder="nama@contoh.com"
                    helper="Digunakan untuk korespondensi resmi."
                    maxlength="150"
                    required
                    :realtime="true"
                    x-model="form.email"
                    @input.debounce.300ms="validate('email')"
                    @blur="touch('email')"
                />

                <x-form.input
                    name="nik"
                    label="NIK"
                    :value="$peserta?->nik"
                    placeholder="16 digit angka"
                    helper="Sesuai KTP, tidak boleh sama antar peserta."
                    maxlength="16"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    required
                    :realtime="true"
                    counter="form.nik ? form.nik.length + '/16 digit' : '0/16 digit'"
                    x-model="form.nik"
                    @input.debounce.200ms="validate('nik')"
                    @blur="touch('nik')"
                />

                <x-form.input
                    name="no_telepon"
                    type="tel"
                    label="Nomor Telepon"
                    :value="$peserta?->no_telepon"
                    placeholder="08xxxxxxxxxx"
                    helper="10–15 digit angka, tanpa spasi atau tanda baca."
                    maxlength="15"
                    inputmode="numeric"
                    required
                    :realtime="true"
                    counter="form.no_telepon ? form.no_telepon.length + ' digit' : '0 digit'"
                    x-model="form.no_telepon"
                    @input.debounce.200ms="validate('no_telepon')"
                    @blur="touch('no_telepon')"
                />

                <x-form.input
                    name="tanggal_lahir"
                    type="date"
                    label="Tanggal Lahir"
                    :value="$peserta?->tanggal_lahir?->format('Y-m-d')"
                    helper="Peserta harus berusia minimal 17 tahun."
                    :max="now()->subYears(17)->format('Y-m-d')"
                    required
                    :realtime="true"
                    x-model="form.tanggal_lahir"
                    @change="touch('tanggal_lahir')"
                />

                <x-form.select
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    :value="$peserta?->jenis_kelamin"
                    :options="['L' => 'Laki-laki', 'P' => 'Perempuan']"
                    required
                />

                <x-form.select
                    name="status"
                    label="Status"
                    :value="$peserta?->status ?? 'aktif'"
                    :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']"
                    required
                />

                <x-form.textarea
                    name="alamat"
                    label="Alamat Lengkap"
                    :value="$peserta?->alamat"
                    placeholder="Jalan, nomor, RT/RW, kelurahan, kecamatan, kota"
                    helper="Minimal 10 karakter."
                    :rows="3"
                    maxlength="255"
                    required
                    :realtime="true"
                    counter="form.alamat ? form.alamat.length + '/10 min' : '0/10 min'"
                    x-model="form.alamat"
                    @input.debounce.300ms="validate('alamat')"
                    @blur="touch('alamat')"
                    class="sm:col-span-2"
                />
            </div>

            <div class="flex flex-col-reverse gap-2 border-t border-gray-100 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                <a href="{{ route('peserta.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $judulTombol }}
                </button>
            </div>
        </div>
    </form>

    <p class="mt-4 max-w-3xl text-xs text-gray-500">
        Kolom bertanda <span class="font-semibold text-red-500">*</span> wajib diisi.
        Jika ada isian yang tidak sesuai, sistem akan menandai kolom tersebut beserta alasannya secara langsung.
    </p>

    <script>
        function formPesertaRealtime() {
            return {
                form: {
                    nama_lengkap: @js(old('nama_lengkap', $peserta?->nama_lengkap ?? '')),
                    email: @js(old('email', $peserta?->email ?? '')),
                    nik: @js(old('nik', $peserta?->nik ?? '')),
                    no_telepon: @js(old('no_telepon', $peserta?->no_telepon ?? '')),
                    tanggal_lahir: @js(old('tanggal_lahir', $peserta?->tanggal_lahir?->format('Y-m-d') ?? '')),
                    alamat: @js(old('alamat', $peserta?->alamat ?? '')),
                },
                touched: {},
                clientErrors: {},
                touch(field) {
                    this.touched[field] = true;
                    this.validate(field);
                },
                validate(field) {
                    const val = (this.form[field] || '').toString().trim();

                    if (field === 'nama_lengkap') {
                        if (!val) {
                            this.clientErrors.nama_lengkap = 'Nama lengkap wajib diisi.';
                        } else if (val.length < 3) {
                            this.clientErrors.nama_lengkap = 'Nama lengkap minimal 3 karakter.';
                        } else if (!/^[a-zA-Z\s]+$/.test(val)) {
                            this.clientErrors.nama_lengkap = 'Nama lengkap hanya boleh berisi huruf dan spasi.';
                        } else {
                            delete this.clientErrors.nama_lengkap;
                        }
                    }

                    if (field === 'email') {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!val) {
                            this.clientErrors.email = 'Email wajib diisi.';
                        } else if (!emailRegex.test(val)) {
                            this.clientErrors.email = 'Format email tidak valid.';
                        } else {
                            delete this.clientErrors.email;
                        }
                    }

                    if (field === 'nik') {
                        if (!val) {
                            this.clientErrors.nik = 'NIK wajib diisi.';
                        } else if (!/^\d{16}$/.test(val)) {
                            this.clientErrors.nik = 'NIK harus terdiri dari 16 digit angka.';
                        } else {
                            delete this.clientErrors.nik;
                        }
                    }

                    if (field === 'no_telepon') {
                        if (!val) {
                            this.clientErrors.no_telepon = 'Nomor telepon wajib diisi.';
                        } else if (!/^\d{10,15}$/.test(val)) {
                            this.clientErrors.no_telepon = 'Nomor telepon hanya boleh berisi angka (10–15 digit).';
                        } else {
                            delete this.clientErrors.no_telepon;
                        }
                    }

                    if (field === 'tanggal_lahir') {
                        if (!val) {
                            this.clientErrors.tanggal_lahir = 'Tanggal lahir wajib diisi.';
                        } else {
                            const lahir = new Date(val);
                            const batas = new Date();
                            batas.setFullYear(batas.getFullYear() - 17);
                            if (lahir > batas) {
                                this.clientErrors.tanggal_lahir = 'Usia minimal 17 tahun.';
                            } else {
                                delete this.clientErrors.tanggal_lahir;
                            }
                        }
                    }

                    if (field === 'alamat') {
                        if (!val) {
                            this.clientErrors.alamat = 'Alamat lengkap wajib diisi.';
                        } else if (val.length < 10) {
                            this.clientErrors.alamat = 'Alamat minimal 10 karakter.';
                        } else {
                            delete this.clientErrors.alamat;
                        }
                    }
                },
                hasClientError(field) {
                    return Boolean(this.touched[field] && this.clientErrors[field]);
                },
                getClientError(field) {
                    return this.clientErrors[field] || '';
                },
                isClientValid(field) {
                    return Boolean(this.touched[field] && !this.clientErrors[field] && this.form[field]);
                },
                totalClientErrors() {
                    return Object.keys(this.clientErrors).length;
                }
            };
        }
    </script>
</x-app-layout>
