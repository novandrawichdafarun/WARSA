<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stok.index') }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-xl border border-transparent hover:border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Tambah Stok Produk</h2>
                <p class="text-xs text-gray-500 mt-0.5">Catat penambahan jumlah stok fisik barang ke dalam sistem.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        {{-- Menggunakan max-w-5xl agar cukup untuk 2 kolom (Kiri dan Kanan) --}}
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

            {{-- Wrapper Grid Utama (Membagi Kiri & Kanan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: KARTU INFORMASI PRODUK --}}
                <div class="space-y-6">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
                        <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-4 w-full text-left">
                            Detail Barang Terpilih</h3>

                        <div id="preview-empty"
                            class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex flex-col items-center justify-center mb-4 transition-all">
                            <span class="text-3xl block mb-2"><x-lucide-package-search
                                    class="w-16 h-16 text-emerald-500" /></span>
                            <p class="text-xs font-semibold text-gray-500">
                                Pilih produk di samping <br>
                                untuk melihat detail
                            </p>
                        </div>

                        {{-- Tampilan Aktif (Muncul saat produk dipilih) --}}
                        <div id="preview-active" class="hidden w-full flex-col items-center animate-fade-in-up ">
                            {{-- Foto Produk (Dengan fallback jika gambar tidak ada) --}}
                            <img id="preview-foto" src="" alt="Foto Produk"
                                class="w-full aspect-square object-cover rounded-2xl shadow-sm border-2 border-dashed border-emerald-300 mb-4 bg-gray-50"
                                onerror="this.src='https://ui-avatars.com/api/?name=Barang&background=10b981&color=fff&size=256'">

                            {{-- Nama Produk --}}
                            <h4 id="preview-nama" class="font-bold text-lg text-gray-800 leading-tight mb-4">Nama Produk
                            </h4>

                            {{-- Kotak Info Stok --}}
                            <div class="w-full grid grid-cols-2 gap-3">
                                <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100 text-center">
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider mb-1">Sisa
                                        Stok</p>
                                    <p id="preview-stok" class="text-2xl font-black text-emerald-700">0</p>
                                </div>
                                <div class="bg-orange-50 rounded-xl p-3 border border-orange-100 text-center">
                                    <p class="text-[10px] text-orange-600 font-bold uppercase tracking-wider mb-1">Batas
                                        Minimal</p>
                                    <p id="preview-minimal" class="text-2xl font-black text-orange-700">0</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- KOLOM KANAN: FORM INPUT UTAMA --}}
                <div class="lg:col-span-2 space-y-6">
                    <form id="form-tambah-stok" method="POST" action="{{ route('stok.store') }}">
                        @csrf

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
                            <h3 class="flex gap-2 font-bold text-base text-gray-800 border-b border-gray-50 pb-3">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Formulir Penambahan Stok
                            </h3>

                            {{-- Form Pilih Produk --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Pilih Produk / Komoditas <span class="text-rose-500">*</span>
                                </label>
                                <select name="product_id" required
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-800 font-medium cursor-pointer">
                                    <option value="">— Silakan Pilih Produk —</option>
                                    @foreach ($produk as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('product_id') == $item->id ? 'selected' : '' }}
                                            data-nama="{{ $item->nama_produk }}" data-stok="{{ $item->stok }}"
                                            data-minimal="{{ $item->stok_minimal }}"
                                            data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : '' }}">
                                            {{ $item->nama_produk }}
                                            @if ($item->stok <= 0)
                                                (Stok Habis)
                                            @elseif ($item->isLowStock())
                                                (Stok Menipis: {{ $item->stok }})
                                            @else
                                                (Stok saat ini: {{ $item->stok }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Form Jumlah Masuk --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Jumlah Ditambahkan <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-lg font-bold text-emerald-500 pointer-events-none">+</span>
                                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                                        required
                                        class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm font-bold text-gray-800 transition-all"
                                        placeholder="0">
                                </div>
                                @error('jumlah')
                                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Form Keterangan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea name="keterangan" rows="3" placeholder="Contoh: Restock bulanan dari supplier A, dll..."
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all resize-none">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </form>

                    {{-- Form Footer Control --}}
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('stok.index') }}"
                            class="w-full sm:w-auto px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors text-center shadow-sm">
                            Batal
                        </a>
                        <button type="submit" form="form-tambah-stok"
                            class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 text-center">
                            Simpan Penambahan
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script JavaScript Interaktif --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectProduk = document.querySelector('select[name="product_id"]');

            // Elemen Kiri (Preview)
            const previewEmpty = document.getElementById('preview-empty');
            const previewActive = document.getElementById('preview-active');

            const txtNama = document.getElementById('preview-nama');
            const txtStok = document.getElementById('preview-stok');
            const txtMinimal = document.getElementById('preview-minimal');
            const imgFoto = document.getElementById('preview-foto');

            function updatePreview() {
                const selected = selectProduk.options[selectProduk.selectedIndex];

                if (selectProduk.value) {
                    // Ambil data dari atribut dataset option terpilih
                    const nama = selected.dataset.nama;
                    const stok = selected.dataset.stok;
                    const minimal = selected.dataset.minimal;
                    let fotoUrl = selected.dataset.foto;

                    // Update Text
                    txtNama.textContent = nama;
                    txtStok.textContent = stok;
                    txtMinimal.textContent = minimal;

                    // Update Gambar (Jika foto kosong, kita beri fallback gambar inisial nama)
                    if (!fotoUrl) {
                        const fallbackName = encodeURIComponent(nama);
                        fotoUrl =
                            `https://ui-avatars.com/api/?name=${fallbackName}&background=10b981&color=fff&size=256&bold=true`;
                    }
                    imgFoto.src = fotoUrl;

                    // Sembunyikan default, munculkan kartu aktif
                    previewEmpty.classList.add('hidden');
                    previewEmpty.classList.remove('flex');
                    previewActive.classList.remove('hidden');
                    previewActive.classList.add('flex');
                } else {
                    // Jika dikembalikan ke "Pilih Produk"
                    previewEmpty.classList.remove('hidden');
                    previewEmpty.classList.add('flex');
                    previewActive.classList.add('hidden');
                    previewActive.classList.remove('flex');
                }
            }

            // Jalankan event listener saat pilihan diganti
            selectProduk.addEventListener('change', updatePreview);

            // Jalankan sekali saat halaman dimuat (untuk old input jika error validasi)
            updatePreview();
        });
    </script>
</x-app-layout>
