<x-guest-layout>
    <div class="text-center space-y-1 mb-4 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mt-1">Terima kasih telah mendaftar! Kami telah mengirimkan 6 digit kode
                verifikasi ke email Anda. Silakan masukkan kode tersebut di bawah ini.</p>
        </div>
    </div>

    <x-auth-session-status
        class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100"
        :status="session('status')" />

    <form method="POST" action="{{ route('verification.verify.otp') }}">
        @csrf
        <div>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <x-text-input id="verification_code"
                    class="block w-full py-3 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-2xl tracking-widest text-center font-bold"
                    type="text" name="verification_code" required autofocus maxlength="6" placeholder="000000" />
            </div>
            <x-input-error :messages="$errors->get('verification_code')" class="mt-2 text-xs" />
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-100 transition-all duration-300 transform active:scale-95 text-center">
                VERIFIKASI KODE
            </button>
        </div>
    </form>

    <div class="border-t border-gray-50 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 bg-white border border-gray-200 hover:bg-gray-50 text-emerald-600 rounded-xl font-bold text-sm transition-colors text-center">
                Kirim Ulang Kode
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 bg-transparent text-gray-500 hover:text-gray-700 rounded-xl font-semibold text-sm transition-colors text-center">
                Keluar dari Akun
            </button>
        </form>
    </div>
</x-guest-layout>
