<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isOwner() || auth()->user()->isSuperAdmin() ? route('dashboard') : route('pos.index') }}"
                        class="flex items-center gap-2 group">
                        {{-- Catatan: h-10 lebih ideal untuk navbar h-16 agar tidak terpotong --}}
                        <x-application-logo class="h-16 w-auto group-hover:scale-105 transition-transform" />
                    </a>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden lg:flex lg:items-center lg:ms-8 lg:space-x-2">

                    @auth
                        {{-- Dashboard Owner & Super Admin --}}
                        @if (auth()->user()->isOwner() || auth()->user()->isSuperAdmin())
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                            {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Dashboard
                            </a>
                        @endif

                        {{-- Menu Khusus Super Admin --}}
                        @if (auth()->user()->isSuperAdmin())
                            <a href="{{ route('super_admin.users.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('super_admin.users.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Manajemen User
                            </a>
                            <a href="{{ route('super_admin.warungs.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('super_admin.warungs.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Manajemen Warung
                            </a>
                            <a href="{{ route('super_admin.commission.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('super_admin.commission.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Komisi
                            </a>
                        @endif

                        {{-- POS — owner + kasir --}}
                        @if (auth()->user()->canAccessPOS())
                            <a href="{{ route('pos.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('pos.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Kasir (POS)
                            </a>
                            <a href="{{ route('transaksi.riwayat') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('transaksi.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Riwayat Transaksi
                            </a>
                        @endif

                        {{-- Menu khusus Owner --}}
                        @if (auth()->user()->isOwner())
                            <a href="{{ route('produk.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('produk.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Produk
                            </a>
                            <a href="{{ route('stok.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('stok.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Stok
                            </a>
                            <a href="{{ route('kategori.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('kategori.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Kategori
                            </a>
                            <a href="{{ route('karyawan.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('karyawan.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Karyawan
                            </a>
                            <a href="{{ route('laporan.index') }}"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-all
                                {{ request()->routeIs('laporan.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-500 hover:text-emerald-600 hover:bg-gray-50 font-semibold' }}">
                                Laporan
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right side (Badge & Profile) --}}
            <div class="hidden lg:flex lg:items-center lg:gap-4">
                @auth
                    {{-- Badge Warung --}}
                    @if (auth()->user()->warung)
                        <span
                            class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">
                            {{ auth()->user()->warung->nama_warung }}
                        </span>
                    @endif

                    {{-- User Dropdown --}}
                    <div x-data="{ dropOpen: false }" class="relative">
                        <button @click="dropOpen = !dropOpen"
                            class="flex items-center gap-2.5 px-2 py-1.5 rounded-full border border-transparent hover:border-gray-200 hover:bg-gray-50 transition-all focus:outline-none">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span
                                class="text-sm font-bold text-gray-700">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <x-lucide-chevron-down class="w-4 h-4 text-gray-400" />
                        </button>

                        {{-- Isi Dropdown --}}
                        <div x-show="dropOpen" @click.away="dropOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 origin-top-right"
                            style="display: none;">

                            <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">
                                    {{ ucfirst(auth()->user()->role) }}</p>
                                <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            @if (auth()->user()->isOwner() || auth()->user()->isSuperAdmin())
                                <a href="{{ route('pengaturan.index') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 mx-1 rounded-xl transition-colors">
                                    <x-lucide-settings class="w-4 h-4" /> Pengaturan Warung
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 mx-1 rounded-xl transition-colors mt-1">
                                    <x-lucide-log-out class="w-4 h-4" /> Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <div class="flex items-center lg:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-xl text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MOBILE MENU ================= --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden border-t border-gray-100 bg-gray-50">
        <div class="px-4 pt-4 pb-6 space-y-1.5">
            @auth
                {{-- Profil Mobile --}}
                <div class="flex items-center gap-3 px-3 py-3 mb-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center shadow-sm flex-shrink-0">
                        <span
                            class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                {{-- Dashboard semua role --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                    Dashboard
                </a>

                {{-- Menu Khusus Super Admin --}}
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.users.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('super_admin.users.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Manajemen User
                    </a>
                    <a href="{{ route('super_admin.warungs.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('super_admin.warungs.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Manajemen Warung
                    </a>
                @endif

                {{-- POS — owner + kasir --}}
                @if (auth()->user()->canAccessPOS())
                    <div class="h-px bg-gray-200 my-2 mx-2"></div>
                    <a href="{{ route('pos.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('pos.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Kasir (POS)
                    </a>
                    <a href="{{ route('transaksi.riwayat') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('transaksi.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Riwayat Transaksi
                    </a>
                @endif

                {{-- Menu Khusus Owner --}}
                @if (auth()->user()->isOwner())
                    <div class="h-px bg-gray-200 my-2 mx-2"></div>
                    <a href="{{ route('produk.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('produk.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Produk
                    </a>
                    <a href="{{ route('kategori.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('kategori.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Kategori
                    </a>
                    <a href="{{ route('karyawan.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('karyawan.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Karyawan
                    </a>
                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('laporan.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        Laporan
                    </a>
                @endif

                <div class="h-px bg-gray-200 my-2 mx-2"></div>
                @if (auth()->user()->isOwner() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('pengaturan.index') }}"
                        class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm font-bold rounded-xl transition-colors {{ request()->routeIs('pengaturan.*') ? 'bg-emerald-100 text-emerald-700' : 'text-gray-600 hover:bg-gray-200' }}">
                        <x-lucide-settings class="w-4 h-4" /> Pengaturan Warung
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-100 rounded-xl transition-colors">
                        <x-lucide-log-out class="w-4 h-4" /> Keluar
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
