<div>
    {{-- Filter Bar --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

        {{-- Filter Produk --}}
        <select wire:model.live="filterProduk"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <option value="">Semua Produk</option>
            @foreach ($produkList as $produk)
                <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
            @endforeach
        </select>

        {{-- Filter Tipe --}}
        <select wire:model.live="filterTipe"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            <option value="">Semua Tipe</option>
            <option value="in">Stok Masuk</option>
            <option value="out">Stok Keluar</option>
        </select>

        {{-- Filter Dari Tanggal --}}
        <input type="date" wire:model.live="filterDariTgl"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">

        {{-- Filter Sampai Tanggal --}}
        <input type="date" wire:model.live="filterSampaiTgl"
            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
    </div>

    {{-- Tombol Reset --}}
    @if ($filterProduk || $filterTipe || $filterDariTgl || $filterSampaiTgl)
        <div class="mb-4">
            <button wire:click="resetFilter"
                class="text-sm text-gray-500 hover:text-red-600 flex items-center gap-1 transition-colors">
                × Hapus semua filter
            </button>
        </div>
    @endif

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Tipe</th>
                    <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Jumlah</th>
                    <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Stok Sebelum</th>
                    <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Stok Sesudah</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Dicatat Oleh</th>
                    <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($movements as $movement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ $movement->created_at->format('d M Y') }}<br>
                            <span class="text-gray-400">{{ $movement->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-3 px-4 font-medium text-gray-800">
                            {{ $movement->product?->nama_produk ?? '(produk dihapus)' }}
                        </td>
                        <td class="py-3 px-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $movement->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $movement->type === 'in' ? '↑ Masuk' : '↓ Keluar' }}
                            </span>
                        </td>
                        <td
                            class="py-3 px-4 text-right font-bold
                            {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                        </td>
                        <td class="py-3 px-4 text-right text-gray-500">{{ $movement->stok_sebelum }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800">{{ $movement->stok_sesudah }}</td>
                        <td class="py-3 px-4 text-gray-500 text-xs">
                            {{ $movement->user?->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-gray-500 text-xs max-w-xs truncate">
                            {{ $movement->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">📋</span>
                                <p class="text-gray-400 text-sm font-medium">Belum ada riwayat pergerakan stok</p>
                                @if ($filterProduk || $filterTipe || $filterDariTgl || $filterSampaiTgl)
                                    <p class="text-gray-400 text-xs">Coba ubah filter pencarian</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 pt-4 border-t border-gray-100">
        {{ $movements->links() }}
    </div>
</div>
