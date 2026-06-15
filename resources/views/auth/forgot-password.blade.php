<x-guest-layout>
    <div class="text-center space-y-1 mb-4 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk mengelola usahamu hari ini.</p>
        </div>
    </div>

    <div
        class="p-2 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 leading-relaxed mb-4 shadow-inner">
        {{ __('Lupa kata sandi usahamu? Jangan khawatir. Silakan masukkan alamat email yang terdaftar, dan kami akan mengirimkan tautan pemulihan untuk mengatur ulang kata sandi baru Anda.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status
        class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100"
        :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </span>

                <x-text-input id="email"
                    class="block w-full pl-11 pr-3 py-3 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="email" name="email" :value="old('email')" required autofocus
                    placeholder="Masukkan Alamat Email Anda" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-100 transition-all duration-300 transform active:scale-95 text-center">
                {{ __('KIRIM KODE PEMULIHAN') }}
            </button>
        </div>

        <div class="text-center mt-2 border-t border-gray-50 flex items-center justify-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-1 text-sm font-bold text-gray-500 hover:text-emerald-600 transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7 7-7" />
                </svg>
                Kembali ke halaman Login
            </a>
        </div>
    </form>
</x-guest-layout>
