<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('produk.index') }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-xl border border-transparent hover:border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Tambah Produk Baru</h2>
                <p class="text-xs text-gray-500 mt-0.5">Input komoditas atau barang baru ke dalam sistem inventaris
                    warung Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Error Validation Messages --}}
            @if ($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2 text-rose-800 font-semibold text-sm mb-2">
                        <span>🛑</span> Terjadi beberapa kesalahan pengisian form:
                    </div>
                    <ul class="text-xs text-rose-600 space-y-1 list-disc list-inside pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Grid Wrapper --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: UNGGAH FOTO --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-4">Foto Produk</h3>

                        {{-- Box Preview Foto Interaktif --}}
                        <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4 overflow-hidden relative group cursor-pointer transition-all hover:border-emerald-400"
                            onclick="document.getElementById('foto-input-sync').click()">

                            <img id="foto-preview" src=""
                                class="hidden w-full h-full object-cover rounded-xl transition-transform duration-300 group-hover:scale-105">

                            <div id="foto-placeholder"
                                class="flex flex-col items-center justify-center w-full h-full p-4 text-center">
                                <span class="text-3xl block mb-2"><x-lucide-image-up
                                        class="w-16 h-16 text-emerald-500" /></span>
                                <p class="text-xs font-semibold text-gray-500">Klik untuk unggah foto</p>
                                <p class="text-[10px] text-gray-400 mt-1">Format PNG, JPG max 2MB</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-400 text-center leading-relaxed">Pratinjau foto otomatis muncul
                            sebelum formulir disimpan.</p>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM INPUT UTAMA --}}
                <div class="lg:col-span-2 space-y-6">

                    <form id="form-tambah-produk" method="POST" action="{{ route('produk.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Hidden File Input --}}
                        <input type="file" id="foto-input-sync" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">

                        {{-- Section 1: Informasi Produk --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                            <h3 class="flex gap-2 font-bold text-base text-gray-800 border-b border-gray-50 pb-3">
                                <x-lucide-notepad-text class="w-6 h-6 text-emerald-500" /> Detail Informasi
                                Barang
                            </h3>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Nama Produk / Komoditas <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required
                                    placeholder="Contoh: Indomie Goreng Spesial"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Group</label>
                                <select name="category_id"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-600 cursor-pointer">
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
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi
                                    Singkat</label>
                                <textarea name="deskripsi" rows="3" placeholder="Tulis rincian, varian, atau spesifikasi barang disini..."
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all resize-none">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        {{-- Section 2: Keuangan --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                            <h3 class="flex gap-2 font-bold text-base text-gray-800 border-b border-gray-50 pb-3">
                                <x-lucide-circle-dollar-sign class="w-6 h-6 text-emerald-500" /> Aturan Finansial
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Jual Konsumen
                                        <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-gray-400 pointer-events-none">Rp</span>
                                        <input type="number" name="harga_jual" min="1" required
                                            value="{{ old('harga_jual') }}" placeholder="0"
                                            class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Beli Dasar
                                        (Modal)</label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-gray-400 pointer-events-none">Rp</span>
                                        <input type="number" name="harga_beli" min="1"
                                            value="{{ old('harga_beli') }}" placeholder="0"
                                            class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 3: Stok Awal & Konfigurasi --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                            <h3 class="flex gap-2 font-bold text-base text-gray-800 border-b border-gray-50 pb-3">
                                <x-lucide-settings class="w-6 h-6 text-emerald-500" /> Stok & Konfigurasi Sistem
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Awal <span
                                            class="text-rose-500">*</span></label>
                                    <input type="number" name="stok" min="0" required
                                        value="{{ old('stok', 0) }}"
                                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Batas Minimum Stok
                                        Alert <span class="text-rose-500">*</span></label>
                                    <input type="number" name="stok_minimal" min="0" required
                                        value="{{ old('stok_minimal', 5) }}"
                                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                </div>
                            </div>

                            <div class="mt-4">
                                <div
                                    class="flex items-center justify-between w-full p-2.5 bg-gray-50 border border-gray-100 rounded-xl h-[46px]">
                                    <span class="text-sm font-semibold text-gray-700 pl-1">Status Penjualan Langsung
                                        Aktif</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Form Footer Control --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('produk.index') }}"
                            class="w-full sm:w-auto px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors text-center shadow-sm">
                            Batal
                        </a>
                        <button type="submit" form="form-tambah-produk"
                            class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 text-center">
                            Simpan Produk Baru
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script Preview File Upload Gambar --}}
    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById('foto-preview');
                    const placeholder = document.getElementById('foto-placeholder');

                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
