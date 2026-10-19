@php
    $bolehUbah = auth()->user()?->isAdmin() ?? false;
    $baris = [
        'Nama Lengkap' => $peserta->nama_lengkap,
        'Email' => $peserta->email,
        'NIK' => $peserta->nik,
        'Nomor Telepon' => $peserta->no_telepon,
        'Tanggal Lahir' => $peserta->tanggal_lahir->translatedFormat('d F Y').' ('.$peserta->labelUsia().' tahun)',
        'Jenis Kelamin' => $peserta->labelJenisKelamin(),
        'Alamat' => $peserta->alamat,
        'Terdaftar' => $peserta->created_at->translatedFormat('d F Y, H:i').' WIB',
    ];
@endphp

<x-app-layout title="Detail Peserta" :subtitle="$peserta->nama_lengkap">
    <x-slot:actions>
        <a href="{{ route('peserta.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
            Kembali
        </a>
        @if ($bolehUbah)
            <a href="{{ route('peserta.edit', $peserta) }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                Ubah Data
            </a>
        @endif
    </x-slot:actions>

    <div class="max-w-3xl rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center gap-4 border-b border-gray-100 px-6 py-5">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-100 text-xl font-bold text-indigo-700">
                {{ strtoupper(substr($peserta->nama_lengkap, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $peserta->nama_lengkap }}</h2>
                <div class="mt-1 flex items-center gap-2">
                    <x-badge-status :status="$peserta->status" />
                    <span class="text-xs text-gray-500">ID #{{ $peserta->id }}</span>
                </div>
            </div>
        </div>

        <dl class="divide-y divide-gray-100">
            @foreach ($baris as $label => $nilai)
                <div class="grid gap-1 px-6 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">{{ $label }}</dt>
                    <dd class="text-sm text-gray-900 sm:col-span-2">{{ $nilai }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</x-app-layout>
