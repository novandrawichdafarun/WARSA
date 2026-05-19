<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stok.index') }}">← kembali</a>
            <h2>Tambah Stok Manual</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">

                @if ($errors->any())
                    {{-- error list --}}
                @endif

                <form method="POST" action="{{ route('stok.store') }}" class="space-y-5">
                    @csrf

                    {{-- Pilih Produk --}}
                    <div>
                        <label>Produk <span class="text-red-500">*</span></label>
                        <select name="product_id" required>
                            <option value="">— Pilih Produk —</option>
                            @foreach ($produk as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('product_id') == $item->id ? 'selected' : '' }}
                                    data-stok="{{ $item->stok }}" data-minimal="{{ $item->stok_minimal }}">
                                    {{ $item->nama_produk }}
                                    (Stok: {{ $item->stok }}
                                    {{ $item->isLowStock() ? '⚠️' : '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p>{{ $message }}</p>
                        @enderror

                        {{-- Info stok setelah pilih produk — update via JS --}}
                        <div id="stok-info" class="hidden mt-2 p-2 bg-gray-50 rounded-lg text-xs text-gray-500">
                            Stok sekarang: <span id="stok-sekarang" class="font-bold">-</span> unit
                            | Minimal: <span id="stok-minimal">-</span> unit
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label>Jumlah Tambah <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" required
                            placeholder="Masukkan jumlah unit yang ditambah">
                        @error('jumlah')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                            placeholder="Contoh: Restock dari supplier, Kiriman gudang, dll">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('stok.index') }}">Batal</a>
                        <button type="submit">Simpan Penambahan Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script: tampilkan info stok setelah pilih produk --}}
    <script>
        document.querySelector('select[name="product_id"]').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const info = document.getElementById('stok-info');

            if (this.value) {
                document.getElementById('stok-sekarang').textContent = selected.dataset.stok;
                document.getElementById('stok-minimal').textContent = selected.dataset.minimal;
                info.classList.remove('hidden');
            } else {
                info.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
