<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-900">Daftar Akun Baru</h2>
        <p class="text-xs text-gray-500 mt-1">Lengkapi data berikut untuk mendapatkan akses ke aplikasi SIPES.</p>
    </div>

    @if ($errors->any())
        <x-alert type="error" :title="'Pendaftaran Gagal ('.$errors->count().' kesalahan)'" :dismissible="false" class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <!-- Name -->
        <div>
            <x-form.input
                name="name"
                label="Nama Lengkap"
                :value="old('name')"
                placeholder="Contoh: Budi Santoso"
                helper="Gunakan nama asli Anda."
                required
                autofocus
                autocomplete="name"
            />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-form.input
                name="email"
                type="email"
                label="Alamat Email"
                :value="old('email')"
                placeholder="nama@contoh.com"
                helper="Email aktif untuk notifikasi dan masuk sistem."
                required
                autocomplete="username"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-form.input
                name="password"
                type="password"
                label="Password"
                placeholder="Minimal 8 karakter"
                helper="Gunakan kombinasi huruf, angka, atau simbol."
                required
                autocomplete="new-password"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-form.input
                name="password_confirmation"
                type="password"
                label="Konfirmasi Password"
                placeholder="Ulangi password di atas"
                helper="Pastikan sama persis dengan isian password."
                required
                autocomplete="new-password"
            />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Daftar Sekarang
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
