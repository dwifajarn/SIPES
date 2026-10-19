<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-900">Masuk ke Akun Anda</h2>
        <p class="text-xs text-gray-500 mt-1">Masukkan kredensial Anda untuk melanjutkan ke sistem.</p>
    </div>

    <!-- Session Status (e.g. password reset link sent) -->
    @if (session('status'))
        <x-alert type="success" :title="session('status')" class="mb-4" />
    @endif

    @if ($errors->any())
        <x-alert type="error" title="Gagal Masuk" :dismissible="false" class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <x-form.input
                name="email"
                type="email"
                label="Alamat Email"
                :value="old('email')"
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
                label="Password"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            />
        </div>

        <!-- Remember Me & Lupa Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-xs text-gray-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Masuk
            </button>
        </div>

        @if (Route::has('register'))
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-600">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
