<div>
    {{-- Search & Filter Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">

            {{-- Search Box --}}
            <div class="relative w-full sm:w-72 group">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                    <x-lucide-search class="w-4 h-4" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk"
                    class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all font-medium text-gray-800">
            </div>

            {{-- Filter Kategori --}}
            <select wire:model.live="filterKategori"
                class="w-full sm:w-48 px-4 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm text-gray-600 transition-all cursor-pointer font-medium">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>

        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto border border-gray-100 rounded-xl shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Produk
                        Info</th>
                    <th class="text-left py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        Kategori</th>
                    <th class="text-right py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Harga
                        Modal</th>
                    <th class="text-right py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Harga
                        Jual</th>
                    <th class="text-center py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Stok
                    </th>
                    <th class="text-right py-4 px-5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($produk as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="py-3 px-5">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-200 overflow-hidden flex-shrink-0">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <x-lucide-package class="w-6 h-6" />
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-gray-800 text-sm group-hover:text-emerald-700 transition-colors">
                                        {{ $item->nama_produk }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-5">
                            <span
                                class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-gray-200">
                                {{ $item->category->nama_kategori ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="py-3 px-5 text-right text-gray-500 font-medium">
                            Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-5 text-right font-black text-emerald-600">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-5 text-center">
                            @if ($item->stok <= 0)
                                <span
                                    class="px-2.5 py-1 bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Habis</span>
                            @elseif ($item->isLowStock())
                                <span
                                    class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider rounded-lg"
                                    title="Batas Minimal: {{ $item->stok_minimal }}">Sisa {{ $item->stok }}</span>
                            @else
                                <span
                                    class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded-lg">{{ $item->stok }}
                                    Unit</span>
                            @endif
                        </td>
                        <td class="py-3 px-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('produk.edit', $item) }}"
                                    class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 hover:border-amber-300 text-amber-500 hover:bg-amber-50 rounded-xl shadow-sm transition-all"
                                    title="Edit Produk">
                                    <x-lucide-square-pen class="w-4 h-4" />
                                </a>
                                <button x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'delete-produk-{{ $item->id }}')"
                                    class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 hover:border-rose-300 text-rose-500 hover:bg-rose-50 rounded-xl shadow-sm transition-all"
                                    title="Hapus Produk">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Hapus Produk --}}
                    <x-modal name="delete-produk-{{ $item->id }}" focusable>
                        <form method="POST" action="{{ route('produk.destroy', $item) }}" class="p-6 text-center">
                            @csrf
                            @method('DELETE')
                            <div
                                class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                                <x-lucide-triangle-alert class="w-8 h-8 text-rose-500" />
                            </div>
                            <h2 class="text-xl text-center font-black text-gray-900 mb-2">Hapus Produk Ini?</h2>
                            <p class="text-sm text-gray-500 mb-6 px-4">
                                Anda yakin ingin menghapus <span
                                    class="font-bold text-gray-800">"{{ $item->nama_produk }}"</span>? Semua data
                                produk dan riwayat stoknya akan terhapus.
                            </p>
                            <div class="flex justify-center gap-3">
                                <button type="button" x-on:click="$dispatch('close')"
                                    class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm active:scale-95">
                                    Ya, Hapus Produk
                                </button>
                            </div>
                        </form>
                    </x-modal>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mb-3">
                                    <x-lucide-package-search class="w-8 h-8 text-gray-300" />
                                </div>
                                <p class="text-sm font-bold text-gray-600">Produk Tidak Ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Katalog Anda masih kosong atau tidak ada produk
                                    yang cocok dengan pencarian.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($produk->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $produk->links() }}
        </div>
    @endif
</div>
