<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-900">Atur Ulang Password</h2>
        <p class="text-xs text-gray-500 mt-1">Silakan masukkan password baru untuk akun Anda.</p>
    </div>

    @if ($errors->any())
        <x-alert type="error" title="Gagal Mengatur Ulang Password" :dismissible="false" class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-form.input
                name="email"
                type="email"
                label="Alamat Email"
                :value="old('email', $request->email)"
                placeholder="nama@contoh.com"
                required
                autofocus
                autocomplete="username"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-form.input
                name="password"
                type="password"
                label="Password Baru"
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
                label="Konfirmasi Password Baru"
                placeholder="Ulangi password baru di atas"
                helper="Pastikan sama persis dengan isian password baru."
                required
                autocomplete="new-password"
            />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Simpan Password Baru
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                &larr; Batalkan dan kembali ke Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
