<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('produk.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800">Tambah Produk Baru</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-medium text-red-700 mb-1">Terdapat beberapa kesalahan:</p>
                    <ul class="text-sm text-red-600 space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kolom Kiri: Foto --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Foto Produk</h3>

                            {{-- Preview Area --}}
                            <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center mb-3 overflow-hidden cursor-pointer"
                                onclick="document.getElementById('foto-input').click()">
                                <img id="foto-preview" src=""
                                    class="hidden w-full h-full object-cover rounded-xl" alt="Preview">
                                <div id="foto-placeholder" class="text-center p-4">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Klik untuk pilih foto</p>
                                    <p class="text-xs text-gray-300 mt-1">JPG, PNG, WebP — maks 2MB</p>
                                </div>
                            </div>

                            <input type="file" id="foto-input" name="foto"
                                accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden"
                                onchange="previewFoto(this)">

                            @error('foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Toggle Aktif --}}
                            <div class="mt-4 flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Produk Aktif</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Data Produk --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Informasi Dasar --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Informasi Produk</h3>
                            <div class="space-y-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Produk <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                                        placeholder="Contoh: Nasi Goreng Spesial" required
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('nama_produk') border-red-400 @enderror">
                                    @error('nama_produk')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="category_id"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="">— Tanpa Kategori —</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ old('category_id') == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat produk (opsional)"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Harga</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Harga Jual <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}"
                                            placeholder="Rp 15000" min="1" required
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('harga_jual') border-red-400 @enderror">
                                    </div>
                                    @error('harga_jual')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                                    <div class="relative">
                                        <input type="number" name="harga_beli" value="{{ old('harga_beli') }}"
                                            placeholder="Rp 10000" min="1"
                                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-medium text-gray-800 mb-4">Stok</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Stok Awal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="stok" value="{{ old('stok', 0) }}"
                                        min="0" required
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Stok Minimal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="stok_minimal" value="{{ old('stok_minimal', 5) }}"
                                        min="0" required
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <p class="text-xs text-gray-400 mt-1">Alert muncul jika stok ≤ nilai ini</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('produk.index') }}"
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                Simpan Produk
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
