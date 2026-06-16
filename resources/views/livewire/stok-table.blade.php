<div>
    {{-- ================= FILTER BAR ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        {{-- Filter Produk --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Filter
                Produk</label>
            <select wire:model.live="filterProduk"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors cursor-pointer font-medium text-gray-700">
                <option value="">Semua Produk</option>
                @foreach ($produkList as $produk)
                    <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tipe --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tipe
                Pergerakan</label>
            <select wire:model.live="filterTipe"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors cursor-pointer font-medium text-gray-700">
                <option value="">Semua Tipe</option>
                <option value="in">Stok Masuk</option>
                <option value="out">Stok Keluar</option>
            </select>
        </div>

        {{-- Filter Dari Tanggal --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Dari
                Tanggal</label>
            <input type="date" wire:model.live="filterDariTgl"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
        </div>

        {{-- Filter Sampai Tanggal --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Sampai
                Tanggal</label>
            <input type="date" wire:model.live="filterSampaiTgl"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
        </div>
    </div>

    {{-- ================= TOMBOL RESET ================= --}}
    <div class="mb-5 flex justify-end h-8">
        @if ($filterProduk || $filterTipe || $filterDariTgl || $filterSampaiTgl)
            <button wire:click="resetFilter"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-colors border border-rose-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Hapus Filter
            </button>
        @endif
    </div>

    {{-- ================= TABEL DATA ================= --}}
    <div class="overflow-x-auto border-t border-gray-50">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Waktu
                    </th>
                    <th class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Produk
                    </th>
                    <th class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tipe
                    </th>
                    <th class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah
                    </th>
                    <th class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Sebelum</th>
                    <th class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Sesudah</th>
                    <th class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Dicatat
                        Oleh</th>
                    <th class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($movements as $movement)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-4 px-6 text-gray-500 text-xs whitespace-nowrap">
                            <span
                                class="font-bold text-gray-700">{{ $movement->created_at->format('d M Y') }}</span><br>
                            <span
                                class="text-[10px] text-gray-400 font-medium">{{ $movement->created_at->format('H:i') }}
                                WIB</span>
                        </td>
                        <td class="py-4 px-6 font-bold text-gray-800">
                            {{ $movement->product?->nama_produk ?? '(Produk Dihapus)' }}
                        </td>
                        <td class="py-4 px-6">
                            <span
                                class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $movement->type === 'in' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-200' }}">
                                {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td
                            class="py-4 px-6 text-right font-black text-base
                            {{ $movement->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                        </td>
                        <td class="py-4 px-6 text-right font-semibold text-gray-400">{{ $movement->stok_sebelum }}</td>
                        <td class="py-4 px-6 text-right font-black text-gray-700">{{ $movement->stok_sesudah }}</td>
                        <td class="py-4 px-6 text-gray-600 text-xs font-medium">
                            {{ $movement->user?->name ?? 'Sistem' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500 text-xs max-w-xs truncate italic">
                            {{ $movement->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-600">Belum ada riwayat pergerakan stok</p>
                                @if ($filterProduk || $filterTipe || $filterDariTgl || $filterSampaiTgl)
                                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan atau hapus filter pencarian
                                        Anda.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================= PAGINATION ================= --}}
    @if ($movements->hasPages())
        <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
            {{ $movements->links() }}
        </div>
    @endif
</div>
