<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2>Manajemen Stok</h2>
            <a href="{{ route('stok.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">+
                Tambah Stok Manual</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                {{-- Card: Total Masuk Hari Ini --}}
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Stok Masuk Hari Ini</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $totalMasukHariIni }}</p>
                    <p class="text-xs text-gray-400 mt-1">unit dari semua produk</p>
                </div>

                {{-- Card: Total Keluar Hari Ini --}}
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Stok Keluar Hari Ini</p>
                    <p class="text-3xl font-bold text-red-500">{{ $totalKeluarHariIni }}</p>
                    <p class="text-xs text-gray-400 mt-1">unit terjual/dikurangi</p>
                </div>

                {{-- Card: Produk Low Stock --}}
                <div
                    class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm
                    {{ $produkLowStock->isNotEmpty() ? 'border-l-4 border-l-red-400' : '' }}">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Produk Stok Menipis</p>
                    <p
                        class="text-3xl font-bold {{ $produkLowStock->isNotEmpty() ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $produkLowStock->count() }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">produk perlu restock</p>
                </div>
            </div>

            {{-- Alert Low Stock — tampil hanya jika ada --}}
            @if ($produkLowStock->isNotEmpty())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm font-semibold text-red-700 mb-3">
                        ⚠️ Produk Berikut Perlu Segera Direstok:
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($produkLowStock as $produk)
                            <a href="{{ route('produk.edit', $produk) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-red-200
                                      rounded-lg text-xs hover:border-red-400 transition-colors group">
                                <span class="font-medium text-gray-800 group-hover:text-red-600">
                                    {{ $produk->nama_produk }}
                                </span>
                                <span class="text-red-600 font-bold">{{ $produk->stok }} sisa</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Tabel Riwayat Stok --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Riwayat Pergerakan Stok</h3>
                </div>
                <div class="p-6">
                    <livewire:stok-table />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
