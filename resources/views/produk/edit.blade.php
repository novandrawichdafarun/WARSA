<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('produk.index') }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Edit Produk</h2>
                <p class="text-xs text-gray-500 mt-0.5">Mengubah data inventaris: <span
                        class="font-semibold text-emerald-600">{{ $produk->nama_produk }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm text-emerald-800">
                    <span class="text-lg">✨</span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: FOTO & MANAJEMEN STOK --}}
                <div class="space-y-6">

                    {{-- Foto Box Premium --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-4">Foto Produk</h3>

                        <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center mb-4 overflow-hidden relative group cursor-pointer transition-all hover:border-emerald-400"
                            onclick="document.getElementById('foto-input-sync').click()">
                            @if ($produk->foto)
                                <img id="foto-preview" src="{{ Storage::url($produk->foto) }}"
                                    class="w-full h-full object-cover rounded-xl transition-transform duration-300 group-hover:scale-105"
                                    alt="{{ $produk->nama_produk }}">
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-xs text-white font-semibold">📸 Ganti Gambar</span>
                                </div>
                            @else
                                <img id="foto-preview" src=""
                                    class="hidden w-full h-full object-cover rounded-xl">
                                <div id="foto-placeholder" class="text-center p-4">
                                    <span class="text-3xl block mb-2">🖼️</span>
                                    <p class="text-xs font-semibold text-gray-500">Klik untuk unggah foto</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Format PNG, JPG max 2MB</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-400 text-center leading-relaxed">Pratinjau foto otomatis muncul
                            sebelum formulir disimpan.</p>
                    </div>

                    {{-- Monitor Saham/Stok --}}
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Stok Tersedia</h3>
                            <p class="text-sm font-medium text-gray-500">Kondisi saat ini</p>
                        </div>
                        <div class="text-right">
                            <h4
                                class="text-4xl font-black {{ $produk->isLowStock() ? 'text-rose-600 animate-pulse' : 'text-emerald-600' }}">
                                {{ $produk->stok }}
                            </h4>
                            @if ($produk->isLowStock())
                                <span
                                    class="text-[10px] bg-rose-50 text-rose-600 font-bold px-2 py-0.5 rounded-md mt-1 inline-block">
                                    ⚠ Limit < {{ $produk->stok_minimal }} </span>
                                    @else
                                        <span
                                            class="text-[10px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded-md mt-1 inline-block">
                                            Aman
                                        </span>
                            @endif
                        </div>
                    </div>

                    {{-- Form Tambah Stok Manual --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-4">Re-Stock / Tambah Stok
                        </h3>
                        <form method="POST" action="{{ route('produk.stok.tambah', $produk) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kuantitas Unit</label>
                                <input type="number" name="jumlah" min="1" placeholder="Masukkan jumlah unit"
                                    required
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan
                                    Catatan</label>
                                <input type="text" name="keterangan" placeholder="Misal: Kulakan Supplier Jaya"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 rounded-xl text-sm transition-all">
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold shadow-sm transition-colors">
                                ⚡ Eksekusi Tambah Stok
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: FORM EDIT UTAMA --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Form Utama Edit Produk --}}
                    <form id="form-edit-utama" method="POST" action="{{ route('produk.update', $produk) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Hidden File Input untuk sinkronisasi klik foto box di kiri --}}
                        <input type="file" id="foto-input-sync" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(this)">

                        {{-- Section Konten Detail --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                            <h3 class="font-bold text-base text-gray-800 border-b border-gray-50 pb-3">📋 Detail
                                Informasi Barang</h3>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Nama Produk / Komoditas <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama_produk"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}" required
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori Group</label>
                                <select name="category_id"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all text-gray-600 cursor-pointer">
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
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi
                                    Singkat</label>
                                <textarea name="deskripsi" rows="3" placeholder="Tulis rincian atau spesifikasi barang disini..."
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            </div>
                        </div>

                        {{-- Section Pricing / Keuangan --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                            <h3 class="font-bold text-base text-gray-800 border-b border-gray-50 pb-3">💰 Aturan
                                Finansial</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga Jual Konsumen
                                        <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-gray-400 pointer-events-none">Rp</span>
                                        <input type="number" name="harga_jual" min="1" required
                                            value="{{ old('harga_jual', $produk->harga_jual) }}"
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
                                            value="{{ old('harga_beli', $produk->harga_beli) }}"
                                            class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section Manajemen Pengaturan Stok Minim & Status --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                            <h3 class="font-bold text-base text-gray-800 border-b border-gray-50 pb-3">⚙️ Konfigurasi
                                Sistem</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Batas Minimum Stok
                                        Alert</label>
                                    <input type="number" name="stok_minimal" min="0" required
                                        value="{{ old('stok_minimal', $produk->stok_minimal) }}"
                                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 rounded-xl text-sm transition-all">
                                </div>
                                <div class="flex items-end">
                                    <div
                                        class="flex items-center justify-between w-full p-2.5 bg-gray-50 border border-gray-100 rounded-xl h-[46px]">
                                        <span class="text-sm font-semibold text-gray-700 pl-1">Status Penjualan
                                            Aktif</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $produk->is_active) ? 'checked' : '' }}
                                                class="sr-only peer">
                                            <div
                                                class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> {{-- FORM UTAMA SELESAI --}}

                    {{-- AKSI FORMS FOOTER CONTROL (DIPEGANG OLEH ATTRIBUTE FORM HTML5) --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                        <button type="submit" form="form-hapus-produk"
                            class="w-full sm:w-auto px-4 py-2.5 border border-rose-200 text-rose-600 rounded-xl text-sm font-bold hover:bg-rose-50 transition-colors text-center">
                            🗑️ Hapus & Arsipkan Produk
                        </button>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a href="{{ route('produk.index') }}"
                                class="w-full sm:w-auto px-5 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors text-center shadow-sm">
                                Batal
                            </a>
                            <button type="submit" form="form-edit-utama"
                                class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 text-center">
                                Simpan Perubahan Data
                            </button>
                        </div>
                    </div>

                    {{-- FORM HAPUS MANDIRI (Diletakkan di luar form utama agar terhindar dari nested-bug!) --}}
                    <form id="form-hapus-produk" method="POST" action="{{ route('produk.destroy', $produk) }}"
                        class="hidden"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $produk->nama_produk }}? Data akan diarsipkan.')">
                        @csrf
                        @method('DELETE')
                    </form>

                    {{-- BAGIAN: RIWAYAT HISTORI STOK --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-sm text-gray-400 uppercase tracking-wider mb-4">📜 Histori Log Stok
                            (10 Terakhir)</h3>
                        @if ($riwayatStok->isEmpty())
                            <div class="text-center py-6 text-gray-400 text-xs">
                                <span class="text-2xl block mb-1">📋</span> Belum ada mutasi log stok barang.
                            </div>
                        @else
                            <div class="overflow-x-auto -mx-6">
                                <div class="inline-block min-w-full align-middle px-6">
                                    <table class="w-full text-xs text-left border-collapse">
                                        <thead>
                                            <tr
                                                class="border-b border-gray-100 bg-gray-50/50 text-gray-400 font-bold uppercase tracking-wider">
                                                <th class="py-2.5 px-3">Tanggal Waktu</th>
                                                <th class="py-2.5 px-3">Tipe</th>
                                                <th class="py-2.5 px-3 text-right">Mutasi</th>
                                                <th class="py-2.5 px-3 text-right">Stok Akhir</th>
                                                <th class="py-2.5 px-3 pl-6">Keterangan Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50 text-gray-600">
                                            @foreach ($riwayatStok as $movement)
                                                <tr class="hover:bg-gray-50/40">
                                                    <td class="py-3 px-3 font-medium whitespace-nowrap">
                                                        {{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                                    <td class="py-3 px-3">
                                                        <span
                                                            class="px-2 py-0.5 rounded-md font-semibold text-[10px] {{ $movement->type === 'in' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                            {{ $movement->type === 'in' ? 'MASUK' : 'KELUAR' }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="py-3 px-3 text-right font-bold {{ $movement->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                        {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                                    </td>
                                                    <td class="py-3 px-3 text-right font-bold text-gray-800">
                                                        {{ $movement->stok_sesudah }}</td>
                                                    <td class="py-3 px-3 pl-6 text-gray-400 max-w-xs truncate">
                                                        {{ $movement->keterangan ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
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
