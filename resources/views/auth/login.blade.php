<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center space-y-1 mb-4 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk untuk mengelola usahamu hari ini.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
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
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    placeholder="Alamat Email Anda" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
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
                    class="block w-full pl-11 pr-3 py-3 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="Kata Sandi Rahasia" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-4 pt-4">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4.5 h-4.5 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                <label for="remember_me" class="ml-2.5 text-sm text-gray-600 cursor-pointer">
                    {{ __('Ingat saya') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-200 transition-all duration-300 transform active:scale-95 text-center">
                {{ __('LOGIN SEKARANG') }}
            </button>
        </div>

        <div class="relative py-2 mt-2">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-100"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white px-3 text-sm text-gray-400">Atau masuk dengan</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Google --}}
            <a href="#"
                class="flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <img src="https://authjs.dev/img/providers/google.svg" alt="Google" class="w-5 h-5">
                <span class="text-sm font-semibold text-gray-700">Google</span>
            </a>
            {{-- Facebook --}}
            <a href="#"
                class="flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <img src="https://authjs.dev/img/providers/facebook.svg" alt="Facebook" class="w-5 h-5">
                <span class="text-sm font-semibold text-gray-700">Facebook</span>
            </a>
        </div>

        <div class="text-center pt-1 mt-0 border-t border-gray-50">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors pl-1">
                    Daftar Warung Baru
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
