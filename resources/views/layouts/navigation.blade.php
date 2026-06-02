<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="h-20 w-auto" />
                    </a>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden sm:flex sm:items-center sm:ms-8 sm:space-x-1">

                    @auth
                        {{-- Dashboard semua role --}}
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                            {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Dashboard
                        </a>

                        {{-- Menu Khusus Super Admin --}}
                        @if (auth()->user()->isSuperAdmin())
                            <a href="{{ route('super_admin.users.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('super_admin.users.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Manajemen User
                            </a>
                            <a href="{{ route('super_admin.warungs.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('super_admin.warungs.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Manajemen Warung
                            </a>
                        @endif

                        {{-- POS — owner + kasir --}}
                        @if (auth()->user()->canAccessPOS())
                            <a href="{{ route('pos.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('pos.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Kasir
                            </a>
                        @endif

                        {{-- Menu khusus Owner --}}
                        @if (auth()->user()->isOwner())
                            <a href="{{ route('produk.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('produk.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Produk
                            </a>

                            <a href="{{ route('stok.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('stok.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Stok
                            </a>
                            <a href="{{ route('kategori.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('kategori.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Kategori
                            </a>
                            <a href="{{ route('karyawan.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('karyawan.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Karyawan
                            </a>
                            <a href="{{ route('laporan.index') }}"
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('laporan.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                Laporan
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right side --}}
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @auth
                    {{-- Badge Warung --}}
                    @if (auth()->user()->warung)
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            {{ auth()->user()->warung->nama_warung }}
                        </span>
                    @endif

                    {{-- User Dropdown --}}
                    <div x-data="{ dropOpen: false }" class="relative">
                        <button @click="dropOpen = !dropOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                            <div class="w-7 h-7 bg-green-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="dropOpen" @click.away="dropOpen = false" x-transition
                            class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if (auth()->user()->isOwner())
                                <a href="{{ route('pengaturan.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    ⚙️ Pengaturan Warung
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    → Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
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

    {{-- Mobile Menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-gray-100">
        <div class="px-4 py-2 space-y-1">
            @auth
                {{-- Dashboard semua role --}}
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>

                {{-- Menu Khusus Super Admin --}}
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('super_admin.users.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Manajemen User</a>
                    <a href="{{ route('super_admin.warungs.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Manajemen Warung</a>
                @endif

                {{-- POS — owner + kasir --}}
                @if (auth()->user()->canAccessPOS())
                    <a href="{{ route('pos.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Kasir / POS</a>
                @endif

                {{-- Menu Khusus Owner --}}
                @if (auth()->user()->isOwner())
                    <a href="{{ route('produk.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Produk</a>
                    <a href="{{ route('kategori.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Kategori</a>
                    <a href="{{ route('karyawan.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Karyawan</a>
                    <a href="{{ route('pengaturan.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                    <a href="{{ route('laporan.index') }}"
                        class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">Laporan</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">Keluar</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
