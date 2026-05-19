<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Utama') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau perkembangan dan performa tokomu hari ini.</p>
            </div>
            @if (isset($warung))
                <div
                    class="bg-green-50 border border-green-200 rounded-xl px-4 py-2 flex items-center gap-2 self-start md:self-auto">
                    <span class="text-xl">🏪</span>
                    <div>
                        <p class="text-xs text-green-600 font-medium">Nama Warung</p>
                        <p class="text-sm font-bold text-gray-800">{{ $warung->nama_warung }}</p>
                    </div>
                </div>
            @endif
        </div>
        {{-- Alert Stok Menipis — tampil hanya jika ada --}}
        @if ($produk_low_stock_list->isNotEmpty())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mt-3">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-amber-800 flex items-center gap-2">
                        ⚠️ Stok Menipis ({{ $produk_low_stock }} produk)
                    </p>
                    <a href="{{ route('stok.index') }}" class="text-xs text-amber-600 hover:text-amber-800 underline">
                        Lihat semua →
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($produk_low_stock_list as $produk)
                        <a href="{{ route('produk.edit', $produk) }}"
                            class="px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs
                          hover:border-amber-400 transition-colors flex items-center gap-2">
                            <span class="font-medium">{{ $produk->nama_produk }}</span>
                            <span class="text-red-600 font-bold">{{ $produk->stok }} sisa</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </x-slot>



    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Bagian 1: Kartu Statistik Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Omset Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-800">Rp
                            {{ number_format($omset_hari_ini, 0, ',', '.') }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        💰
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Transaksi Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $total_transaksi_hari_ini }} <span
                                class="text-sm font-normal text-gray-500">Nota</span></h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        🛒
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Produk</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $total_produk }} <span
                                class="text-sm font-normal text-gray-500">Item</span></h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl font-bold">
                        📦
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-md">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Stok Menipis</p>
                        <h3 class="text-2xl font-bold {{ $produk_low_stock > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $produk_low_stock }} <span class="text-sm font-normal text-gray-500">Item</span>
                        </h3>
                    </div>
                    <div
                        class="w-12 h-12 {{ $produk_low_stock > 0 ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-gray-100 text-gray-400' }} rounded-xl flex items-center justify-center text-xl font-bold">
                        ⚠️
                    </div>
                </div>

            </div>

            {{-- Bagian 2: Tombol Aksi Cepat (UX Improvement) --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat Menu Manajemen</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('pos.index') }}"
                        class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                        <span
                            class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">🖥️</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Buka Kasir (POS)</p>
                            <p class="text-xs text-gray-400">Mulai transaksi baru</p>
                        </div>
                    </a>

                    <a href="{{ route('produk.create') }}"
                        class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                        <span
                            class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">➕</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Tambah Produk</p>
                            <p class="text-xs text-gray-400">Input produk baru</p>
                        </div>
                    </a>

                    <a href="{{ route('karyawan.index') }}"
                        class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                        <span
                            class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">👥</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Kelola Karyawan</p>
                            <p class="text-xs text-gray-400">Manajemen hak akses</p>
                        </div>
                    </a>

                    <a href="{{ route('pengaturan.index') }}"
                        class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                        <span
                            class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">⚙️</span>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">Pengaturan</p>
                            <p class="text-xs text-gray-400">Konfigurasi profile warung</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
