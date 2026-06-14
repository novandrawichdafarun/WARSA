<div>
    {{-- Search & Filter Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <x-lucide-search class="w-4 h-4" />
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk..."
                    class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm w-full transition-all">
            </div>

            <select wire:model.live="filterKategori"
                class="px-7 py-2 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm text-gray-600 transition-all cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus"
                class="px-7 py-2 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm text-gray-600 transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <button wire:click="resetFilters"
            class="flex px-4 py-2 border border-gray-200 hover:border-gray-300 bg-white hover:bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold transition-all w-full md:w-auto text-center shadow-sm">
            <x-lucide-rotate-ccw class="w-4 h-4 mr-1 mt-0.5" /> Reset Filter
        </button>
    </div>

    {{-- Tabel Produk Responsif --}}
    <div class="overflow-x-auto -mx-6">
        <div class="inline-block min-w-full align-middle px-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-20">Foto</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Produk
                        </th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Harga Jual</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Stok</th>
                        <th class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th
                            class="py-3.5 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right w-40">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($produk as $item)
                        <tr class="hover:bg-gray-50/60 transition-colors group" wire:key="produk-{{ $item->id }}">
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if ($item->foto)
                                    <img src="{{ Storage::url($item->foto) }}"
                                        class="w-12 h-12 object-cover rounded-xl shadow-sm border border-gray-100 group-hover:scale-105 transition-transform">
                                @else
                                    <div
                                        class="w-12 h-12 bg-gray-100 border border-dashed border-gray-200 rounded-xl flex items-center justify-center">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">No
                                            Pic</span>
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-4 font-semibold text-gray-800 text-sm sm:text-base">
                                {{ $item->nama_produk }}</td>

                            <td class="py-4 px-4 text-sm text-gray-500">
                                <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-xs font-medium">
                                    {{ $item->category?->nama_kategori ?? '-' }}
                                </span>
                            </td>

                            <td class="py-4 px-4 font-medium text-gray-800 text-sm">
                                {{ $item->formatted_harga_jual ?? 'Rp ' . number_format($item->harga_jual, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-4 text-sm">
                                @if ($item->stok <= 0)
                                    <div class="inline-flex flex-col">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 font-bold text-xs inline-flex items-center gap-1">
                                            <x-lucide-triangle-alert stroke-width="2" class="w-4 h-4" />
                                            <span class="text-[10px] text-red-500 font-bold mt-0.5 pl-1">Habis!</span>
                                        </span>
                                    </div>
                                @elseif ($item->isLowstock())
                                    <div class="inline-flex flex-col">
                                        <span
                                            class="px-2.5 py-0.5 rounded-full bg-yellow-50 text-yellow-600 font-bold text-xs inline-flex items-center gap-1">
                                            <x-lucide-triangle-alert stroke-width="2" class="w-4 h-4" />
                                            {{ $item->stok }}
                                        </span>
                                        <span class="text-[10px] text-yellow-500 font-bold mt-0.5 pl-1">Menipis!</span>
                                    </div>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-700 font-medium text-xs">
                                        {{ $item->stok }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $item->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="py-4 px-4 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('produk.edit', $item) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-blue-300 text-blue-600 hover:bg-blue-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                        <x-lucide-square-pen class="w-3 h-3 mr-0.5" /> Edit
                                    </a>
                                    <button x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-produk-{{ $item->id }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-lg shadow-sm transition-all">
                                        <x-lucide-trash-2 class="w-3 h-3 mr-0.5" /> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Delete User --}}
                        <x-modal name="delete-produk-{{ $item->id }}" focusable>
                            <form method="POST" action="{{ route('produk.destroy', $item) }}" class="p-6 text-center">
                                @csrf
                                @method('DELETE')
                                <div
                                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <x-lucide-triangle-alert stroke-width="2" class="w-8 h-8 text-red-500" />
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Produk Ini?</h2>
                                <p class="text-sm text-gray-500 mb-6">
                                    Hapus produk {{ $item->nama_produk }}? Data tidak bisa dikembalikan.
                                </p>

                                <div class="flex justify-center gap-3">
                                    <button type="button" x-on:click="$dispatch('close')"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                        Ya, Hapus Produk
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    @empty
                        {{-- Keadaan Kosong (Empty State) --}}
                        <tr>
                            <td colspan="7" class="text-center text-gray-400 py-12">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <span class="text-4xl mb-2"><x-lucide-package class="w-12 h-12" /></span>
                                    <p class="text-sm font-bold text-gray-500">Tidak ada produk ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau
                                        bersihkan filter Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Navigasi Halaman (Pagination) --}}
    <div class="mt-6 pt-4 border-t border-emerald-100">
        {{ $produk->links() }}
    </div>
</div>
