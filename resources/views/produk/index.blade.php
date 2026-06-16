<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Manajemen Produk
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola katalog barang, harga, dan stok warung Anda.</p>
            </div>
            <div>
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Produk Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm">
                    <div class="p-1 bg-emerald-500 rounded-full text-white flex-shrink-0">
                        <x-lucide-check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Wrapper Kartu Utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-package-search class="w-5 h-5 text-emerald-500" /> Katalog Produk
                    </h3>
                </div>
                <div class="p-6">
                    <livewire:produk-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
