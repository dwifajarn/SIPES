<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-900">Lupa Password?</h2>
        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            Tidak masalah. Masukkan alamat email yang terdaftar pada akun Anda, dan kami akan mengirimkan tautan untuk mengatur ulang password baru.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <x-alert type="success" :title="session('status')" class="mb-4" />
    @endif

    @if ($errors->any())
        <x-alert type="error" title="Gagal Mengirim Tautan" :dismissible="false" class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <x-form.input
                name="email"
                type="email"
                label="Alamat Email Terdaftar"
                :value="old('email')"
                placeholder="nama@contoh.com"
                required
                autofocus
            />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Kirim Tautan Reset Password
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                &larr; Kembali ke halaman Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
