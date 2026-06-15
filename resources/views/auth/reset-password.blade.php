<x-guest-layout>
    <div class="text-center space-y-1 mb-4 mt-0">
        <div class="flex justify-center">
            <x-application-logo class="h-40 w-auto" />
        </div>
        <div>
            <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight">Buat Sandi Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Sandi harus kuat dan mudah diingat.</p>
        </div>
    </div>

    <x-auth-session-status
        class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100"
        :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <div class="mt-4">
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
                    type="password" name="password" required autocomplete="new-password"
                    placeholder="Kata Sandi Baru Anda" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="mt-4">
            <div class="relative group">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                <x-text-input id="password_confirmation"
                    class="block w-full pl-11 pr-3 py-3 border-t-0 border-l-0 border-r-0 border-b border-gray-200 focus:border-emerald-500 focus:ring-0 rounded-none placeholder:text-gray-300 text-base"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="Ulangi Kata Sandi Baru" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="pt-6">
            <button type="submit"
                class="w-full py-3 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-100 transition-all duration-300 transform active:scale-95 text-center">
                SIMPAN SANDI BARU
            </button>
        </div>
    </form>
</x-guest-layout>
