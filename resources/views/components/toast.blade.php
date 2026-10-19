@php
    $pesan = session('pesan');
    $status = session('status');

    $adaToast = ! empty($pesan) || ! empty($status);
    $tipe = $pesan['type'] ?? 'success';
    $judul = $pesan['title'] ?? ($status ? 'Berhasil' : 'Pemberitahuan');
    $isi = $pesan['body'] ?? $status ?? '';

    $warna = match ($tipe) {
        'success' => [
            'bg' => 'bg-white border-green-200 text-green-900',
            'icon_bg' => 'bg-green-100 text-green-600',
            'bar' => 'bg-green-500',
        ],
        'warning' => [
            'bg' => 'bg-white border-amber-200 text-amber-900',
            'icon_bg' => 'bg-amber-100 text-amber-600',
            'bar' => 'bg-amber-500',
        ],
        'error' => [
            'bg' => 'bg-white border-red-200 text-red-900',
            'icon_bg' => 'bg-red-100 text-red-600',
            'bar' => 'bg-red-500',
        ],
        default => [
            'bg' => 'bg-white border-blue-200 text-blue-900',
            'icon_bg' => 'bg-blue-100 text-blue-600',
            'bar' => 'bg-blue-500',
        ],
    };
@endphp

@if ($adaToast)
    <div
        x-data="{
            tampil: true,
            durasi: 5000,
            sisa: 5000,
            interval: null,
            jeda: false,
            mulai() {
                const langkah = 50;
                this.interval = setInterval(() => {
                    if (!this.jeda) {
                        this.sisa -= langkah;
                        if (this.sisa <= 0) {
                            this.tutup();
                        }
                    }
                }, langkah);
            },
            tutup() {
                this.tampil = false;
                clearInterval(this.interval);
            }
        }"
        x-init="mulai()"
        x-show="tampil"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @mouseenter="jeda = true"
        @mouseleave="jeda = false"
        class="fixed top-5 right-5 z-50 max-w-sm w-full overflow-hidden rounded-xl border shadow-xl {{ $warna['bg'] }}"
        role="alert"
        aria-live="assertive"
    >
        <div class="p-4 flex items-start gap-3">
            <div class="flex-shrink-0 h-9 w-9 rounded-lg flex items-center justify-center {{ $warna['icon_bg'] }}">
                @if ($tipe === 'success')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @elseif ($tipe === 'error')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                @elseif ($tipe === 'warning')
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </div>

            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-bold text-gray-900 leading-none">{{ $judul }}</p>
                <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ $isi }}</p>
            </div>

            <button
                type="button"
                @click="tutup()"
                class="flex-shrink-0 -mr-1 -mt-1 p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="Tutup notifikasi"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Progress countdown bar --}}
        <div class="h-1 w-full bg-gray-100 overflow-hidden">
            <div
                class="h-full {{ $warna['bar'] }} transition-all duration-75 ease-linear"
                :style="'width: ' + ((sisa / durasi) * 100) + '%'"
            ></div>
        </div>
    </div>
@endif
