<x-guest-layout>
    {{-- HEADER FORM --}}
    <div class="text-center space-y-1 mb-0 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Daftar Akun Warung</h2>
            <p class="text-gray-500 text-sm mt-1">Mulai langkah digitalisasi operasional tokomu sekarang.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>

                <x-text-input id="name"
                    class="block w-full pl-11 pr-3 py-2.5 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    placeholder="Nama Lengkap Pemilik" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs" />
        </div>

        <!-- Email Address -->
        <div class="mt-2">
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </span>

                <x-text-input id="email"
                    class="block w-full pl-11 pr-3 py-2.5 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="email" name="email" :value="old('email')" required autocomplete="username"
                    placeholder="Alamat Email Usaha" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs" />
        </div>

        <!-- Password -->
        <div class="mt-2">
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>

                <x-text-input id="password"
                    class="block w-full pl-11 pr-3 py-2.5 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="password" name="password" required autocomplete="new-password"
                    placeholder="Buat Kata Sandi Baru" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-2">
            <div class="relative group">
                {{-- Ikon Gembok Centang (Lock-Check Icon) --}}
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>

                <x-text-input id="password_confirmation"
                    class="block w-full pl-11 pr-3 py-2.5 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="Ulangi Kata Sandi Baru" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs" />
        </div>

        <div class="flex items-start pt-4">
            <div class="flex items-center h-5">
                <input id="terms" type="checkbox" required
                    class="w-4.5 h-4.5 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 cursor-pointer">
            </div>
            <div class="ml-2.5 text-xs leading-relaxed text-gray-500">
                Saya menyetujui <a href="#"
                    class="text-emerald-600 hover:text-emerald-700 font-semibold underline">Syarat Ketentuan</a> dan <a
                    href="#" class="text-emerald-600 hover:text-emerald-700 font-semibold underline">Kebijakan
                    Privasi</a> penggunaan layanan sistem kelola Siwarung POS.
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-100 transition-all duration-300 transform active:scale-95 text-center">
                {{ __('DAFTAR WARUNG BARU') }}
            </button>
        </div>

        <div class="text-center pt-1 mt-0 border-t border-gray-50">
            <p class="text-sm text-gray-600">
                Sudah memiliki akun terdaftar?
                <a href="{{ route('login') }}"
                    class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors pl-1">
                    Masuk Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
