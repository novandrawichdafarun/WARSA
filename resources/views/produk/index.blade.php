<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manajemen Produk') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola stok, kategori, dan harga jual barang warung Anda secara
                    real-time.</p>
            </div>
            <div>
                <a href="{{ route('produk.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">
                    <span>➕</span> Tambah Produk Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Wrapper Kartu Utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Produk</h3>
                </div>
                <div class="p-6">
                    <livewire:produk-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
