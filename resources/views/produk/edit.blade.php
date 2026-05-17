<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('produk.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800">Edit Produk</h2>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600 text-base">{{ $produk->nama_produk }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <span class="text-green-600">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Foto + Stok Info --}}
                <div class="space-y-4">

                    {{-- Foto --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">Foto Produk</h3>

                        <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-3 overflow-hidden cursor-pointer"
                            onclick="document.getElementById('foto-input').click()">
                            @if ($produk->foto)
                                <img id="foto-preview" src="{{ Storage::url($produk->foto) }}"
                                    class="w-full h-full object-cover rounded-xl" alt="{{ $produk->nama_produk }}">
                            @else
                                <img id="foto-preview" src=""
                                    class="hidden w-full h-full object-cover rounded-xl">
                                <div id="foto-placeholder" class="text-center p-4">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Klik untuk ganti foto</p>
                                </div>
                            @endif
                        </div>

                        <input type="file" id="foto-input" name="foto_temp" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">
                        <p class="text-xs text-gray-400 text-center">Ganti foto melalui form edit di bawah</p>
                    </div>

                    {{-- Info Stok Sekarang --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-3">Stok Sekarang</h3>
                        <div class="text-center">
                            <p
                                class="text-4xl font-bold {{ $produk->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                                {{ $produk->stok }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">unit tersedia</p>
                            @if ($produk->isLowStock())
                                <div class="mt-2 px-3 py-1 bg-red-100 text-red-600 text-xs rounded-full inline-block">
                                    ⚠ Stok Rendah! Min: {{ $produk->stok_minimal }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form Tambah Stok --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">Tambah Stok Manual</h3>
                        <form method="POST" action="{{ route('produk.stok.tambah', $produk) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Tambah</label>
                                <input type="number" name="jumlah" min="1" placeholder="0" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                                <input type="text" name="keterangan" placeholder="Contoh: Restock dari supplier"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                                + Tambah Stok
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Form Edit --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Form Edit Produk --}}
                    <form method="POST" action="{{ route('produk.update', $produk) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Produk --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                            <h3 class="font-medium text-gray-800 mb-4">Informasi Produk</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Produk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_produk"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}" required
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="category_id"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">— Tanpa Kategori —</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ old('category_id', $produk->category_id) == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto</label>
                                    <input type="file" name="foto" accept="image/*"
                                        class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                            <h3 class="font-medium text-gray-800 mb-4">Harga</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Harga Jual <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span <input type="number" name="harga_jual" min="1" required
                                            value="{{ old('harga_jual', $produk->harga_jual) }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                                    <div class="relative">
                                        <span <input type="number" name="harga_beli" min="1"
                                            value="{{ old('harga_beli', $produk->harga_beli) }}"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stok Minimal + Toggle --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                            <h3 class="font-medium text-gray-800 mb-4">Pengaturan</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimal</label>
                                    <input type="number" name="stok_minimal" min="0" required
                                        value="{{ old('stok_minimal', $produk->stok_minimal) }}"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div class="flex items-center">
                                    <div class="flex items-center justify-between w-full p-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm font-medium text-gray-700">Produk Aktif</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $produk->is_active) ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            {{-- Hapus Produk --}}
                            <form method="POST" action="{{ route('produk.destroy', $produk) }}"
                                onsubmit="return confirm('Yakin hapus produk {{ $produk->nama_produk }}? Produk akan diarsipkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2.5 border border-red-300 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors">
                                    Hapus Produk
                                </button>
                            </form>

                            <div class="flex gap-3">
                                <a href="{{ route('produk.index') }}"
                                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Riwayat Stok --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-medium text-gray-800 mb-4">Riwayat Stok (10 Terakhir)</h3>
                        @if ($riwayatStok->isEmpty())
                            <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat pergerakan stok.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-100">
                                            <th class="text-left py-2 text-xs font-medium text-gray-500">Waktu</th>
                                            <th class="text-left py-2 text-xs font-medium text-gray-500">Tipe</th>
                                            <th class="text-right py-2 text-xs font-medium text-gray-500">Jumlah</th>
                                            <th class="text-right py-2 text-xs font-medium text-gray-500">Stok Akhir
                                            </th>
                                            <th class="text-left py-2 text-xs font-medium text-gray-500">Keterangan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach ($riwayatStok as $movement)
                                            <tr>
                                                <td class="py-2 text-gray-500 text-xs">
                                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="py-2">
                                                    <span
                                                        class="px-2 py-0.5 rounded text-xs font-medium
                                                        {{ $movement->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                        {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="py-2 text-right font-medium
                                                    {{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                                </td>
                                                <td class="py-2 text-right text-gray-700 font-medium">
                                                    {{ $movement->stok_sesudah }}
                                                </td>
                                                <td class="py-2 text-gray-500 text-xs">
                                                    {{ $movement->keterangan ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById('foto-preview');
                    const placeholder = document.getElementById('foto-placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
