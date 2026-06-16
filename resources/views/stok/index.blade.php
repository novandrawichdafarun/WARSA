<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Manajemen Stok
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau dan kelola pergerakan fisik produk warung Anda secara
                    real-time.</p>
            </div>
            <div>
                <a href="{{ route('stok.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Stok Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= SUMMARY CARDS ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                {{-- Card: Stok Masuk --}}
                <div
                    class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <x-lucide-arrow-down-to-line class="w-6 h-6" />
                        </div>
                        <span
                            class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">
                            Hari Ini
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-100 mb-1 font-medium">Stok Masuk</p>
                        <p class="text-3xl font-black tracking-tight">{{ $totalMasukHariIni }} <span
                                class="text-lg font-medium text-emerald-200">Unit</span></p>
                    </div>
                </div>

                {{-- Card: Stok Keluar --}}
                <div
                    class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl p-6 text-white shadow-md shadow-rose-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <x-lucide-arrow-up-from-line class="w-6 h-6" />
                        </div>
                        <span
                            class="text-rose-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">
                            Hari Ini
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-rose-100 mb-1 font-medium">Stok Keluar (Terjual)</p>
                        <p class="text-3xl font-black tracking-tight">{{ $totalKeluarHariIni }} <span
                                class="text-lg font-medium text-rose-200">Unit</span></p>
                    </div>
                </div>

                {{-- Card: Low Stock --}}
                <div
                    class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-md shadow-amber-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <x-lucide-alert-triangle class="w-6 h-6" />
                        </div>
                        <span
                            class="text-amber-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">
                            Peringatan
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-amber-100 mb-1 font-medium">Produk Menipis</p>
                        <p class="text-3xl font-black tracking-tight">{{ $produkLowStock->count() }} <span
                                class="text-lg font-medium text-amber-200">Produk</span></p>
                    </div>
                </div>

            </div>

            {{-- ================= ALERT LOW STOCK ================= --}}
            @if ($produkLowStock->isNotEmpty())
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-sm animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-amber-100 text-amber-600 rounded-xl flex-shrink-0">
                            <x-lucide-triangle-alert class="w-5 h-5" />
                        </div>
                        <p class="text-sm font-bold text-amber-900">Segera Lakukan Restock! Produk berikut hampir habis:
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach ($produkLowStock as $produk)
                            <a href="{{ route('produk.edit', $produk) }}"
                                class="inline-flex items-center gap-3 px-4 py-2 bg-white border border-amber-200 rounded-xl text-xs hover:border-amber-400 hover:shadow-sm transition-all group">
                                <span class="font-bold text-gray-700 group-hover:text-amber-700 transition-colors">
                                    {{ $produk->nama_produk }}
                                </span>
                                @if ($produk->stok <= 0)
                                    <span
                                        class="px-2 py-1 bg-rose-100 text-rose-700 font-black rounded-lg uppercase tracking-wider text-[10px]">Habis</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-amber-100 text-amber-700 font-black rounded-lg uppercase tracking-wider text-[10px]">Sisa
                                        {{ $produk->stok }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ================= TABEL RIWAYAT STOK ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-history class="w-5 h-5 text-emerald-500" /> Riwayat Pergerakan Stok
                    </h3>
                </div>
                <div class="p-6">
                    <livewire:stok-table />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
