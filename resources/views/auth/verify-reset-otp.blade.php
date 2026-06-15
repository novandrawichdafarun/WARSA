<x-guest-layout>
    <div class="text-center space-y-1 mb-4 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Cek Email Anda</h2>
            <p class="text-gray-500 text-sm mt-1">Kami telah mengirimkan kode pemulihan.</p>
        </div>
    </div>

    <div
        class="p-2 bg-gray-50 border border-gray-100 rounded-xl text-sm text-gray-600 leading-relaxed mb-4 shadow-inner text-center">
        Masukkan 6 digit kode pemulihan sandi yang dikirimkan ke email <b>{{ session('reset_email') }}</b>.
    </div>

    <form method="POST" action="{{ route('password.verify.otp') }}">
        @csrf
        <div>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </span>
                <x-text-input id="code"
                    class="block w-full border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-2xl tracking-widest text-center font-bold"
                    type="text" name="code" required autofocus maxlength="6" placeholder="000000" />
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-xs" />
        </div>

        <div class="pt-6">
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-100 transition-all duration-300 transform active:scale-95 text-center">
                VERIFIKASI KODE
            </button>
        </div>
    </form>
</x-guest-layout>
