<div class="flex h-[calc(130vh)] bg-gray-50/50">

    {{-- Alert Notifikasi Kustom (Muncul dari Atas Kanan) --}}
    <div x-data="{ tampil: false, pesan: '' }"
        @tampilkan-alert.window="tampil = true; pesan = $event.detail.pesan; setTimeout(() => tampil = false, 4000)">
        <div x-show="tampil" style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            class="fixed top-6 right-6 z-50 bg-gray-900 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-4">

            <div
                class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center animate-bounce shadow-lg shadow-emerald-500/30">
                <x-lucide-bell-ring class="w-5 h-5 text-white" />
            </div>
            <div>
                <p class="font-bold text-sm text-emerald-400">Pemberitahuan</p>
                <p class="text-sm font-medium" x-text="pesan"></p>
            </div>

            <button @click="tampil = false" class="ml-2 text-gray-400 hover:text-white transition-colors">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </div>
    </div>

    {{-- ================= SISI KIRI: Katalog Produk ================= --}}
    <div class="flex-1 flex flex-col overflow-hidden border-r border-gray-200/60 bg-white">

        {{-- Header Kiri: Pencarian & Filter Kategori --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-white z-10 shadow-sm shadow-gray-100/50">
            <div class="flex flex-col sm:flex-row items-center gap-4">

                {{-- Kolom Pencarian --}}
                <div class="relative flex-1 w-full group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                        <x-lucide-search class="w-5 h-5" />
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama produk atau SKU..."
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-gray-800 transition-all placeholder-gray-400">
                </div>

                {{-- Kolom Filter Kategori --}}
                <div class="w-full sm:w-64 relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                        <x-lucide-tags class="w-5 h-5" />
                    </div>
                    <select wire:model.live="filterKategori"
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl text-sm font-medium text-gray-700 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- Pesan Error Validasi / Stok --}}
        @if ($errorMessage)
            <div
                class="mx-6 mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-up">
                <div class="mt-0.5 text-rose-500"><x-lucide-triangle-alert class="w-5 h-5" /></div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-rose-800">Gagal menambahkan produk</p>
                    <p class="text-xs font-medium text-rose-600 mt-1">{{ $errorMessage }}</p>
                </div>
                <button wire:click="$set('errorMessage', null)"
                    class="text-rose-400 hover:text-rose-600 transition-colors">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        @endif

        {{-- Grid Katalog Produk --}}
        <div class="flex-1 overflow-y-auto p-5 bg-gray-50/30">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 pb-20">

                @forelse ($produk as $item)
                    <button wire:click="tambahKeKeranjang({{ $item->id }})"
                        class="relative flex flex-col bg-white border border-gray-200 rounded-2xl overflow-hidden hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 text-left group
                        {{ $item->stok <= 0 ? 'opacity-50 grayscale cursor-not-allowed' : 'active:scale-95' }}"
                        {{ $item->stok <= 0 ? 'disabled' : '' }}>

                        {{-- Label Peringatan Stok (Mengambang) --}}
                        @if ($item->stok <= 0)
                            <div
                                class="absolute top-2 right-2 z-10 bg-rose-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-sm">
                                Habis
                            </div>
                        @elseif ($item->isLowStock())
                            <div
                                class="absolute top-2 right-2 z-10 bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-sm">
                                Sisa {{ $item->stok }}
                            </div>
                        @endif

                        {{-- Area Foto Produk --}}
                        <div class="w-full aspect-[4/3] bg-gray-100 overflow-hidden relative">
                            @if ($item->foto)
                                <img src="{{ Storage::url($item->foto) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full flex flex-col items-center justify-center text-gray-300 group-hover:text-emerald-400 group-hover:bg-emerald-50 transition-colors duration-300">
                                    <x-lucide-package class="w-10 h-10 mb-1" />
                                </div>
                            @endif

                            {{-- Overlay Add to Cart (Hanya muncul saat dihover dan stok tersedia) --}}
                            @if ($item->stok > 0)
                                <div
                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div
                                        class="bg-white text-gray-900 rounded-full p-3 shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                        <x-lucide-plus class="w-6 h-6" />
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Area Info Teks --}}
                        <div class="p-4 flex-1 flex flex-col justify-between w-full">
                            <div>
                                <p class="text-xs font-bold text-gray-400 mb-1 truncate">
                                    {{ $item->category->nama_kategori ?? 'Tanpa Kategori' }}</p>
                                <p
                                    class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-emerald-700 transition-colors">
                                    {{ $item->nama_produk }}</p>
                            </div>
                            <div class="mt-3">
                                <p class="text-base font-black text-emerald-600">
                                    Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <x-lucide-package-search class="w-12 h-12 text-gray-400" />
                        </div>
                        <h4 class="text-lg font-bold text-gray-700">Produk Tidak Ditemukan</h4>
                        <p class="text-sm text-gray-500 mt-1 max-w-sm">Coba gunakan kata kunci pencarian yang lain atau
                            pilih kategori yang berbeda.</p>
                    </div>
                @endforelse

            </div>

            {{-- Navigasi Paginasi --}}
            @if ($produk->hasPages())
                <div class="pt-4 border-t border-gray-200/60">
                    {{ $produk->links() }}
                </div>
            @endif
        </div>
    </div>


    {{-- ================= SISI KANAN: Keranjang & Pembayaran ================= --}}
    <div
        class="w-full md:w-[350px] lg:w-[400px] flex flex-col bg-white border-l border-gray-200/60 shadow-xl shadow-gray-200/50 z-20">

        {{-- Header Keranjang --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
            <h3 class="font-black text-lg text-gray-800 flex items-center gap-2">
                <x-lucide-shopping-cart class="w-5 h-5 text-emerald-500" /> Keranjang
                @if ($totalItems > 0)
                    <span
                        class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded-lg px-2.5 py-1 ml-1">
                        {{ $totalItems }} Item
                    </span>
                @endif
            </h3>
            @if (!empty($keranjang) && !$showQris)
                <button type="button" @click="$dispatch('open-modal', 'kosongkan-keranjang')"
                    class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">
                    Hapus Semua
                </button>

                <x-modal name="kosongkan-keranjang" focusable>
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <x-lucide-triangle-alert stroke-width="2" class="w-8 h-8 text-rose-500" />
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Kosongkan Keranjang?</h2>
                        <p class="text-sm text-gray-500 mb-6">
                            Anda yakin ingin mengosongkan seluruh isi keranjang ini? Data tidak bisa dikembalikan.
                        </p>

                        <div class="flex justify-center gap-3">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="button" wire:click="kosongkanKeranjang" x-on:click="$dispatch('close')"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                Ya, Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                </x-modal>
            @endif
        </div>

        {{-- MODE 1: TAMPILAN KERANJANG NORMAL --}}
        @if (!$showQris)

            {{-- Daftar Belanjaan --}}
            <div class="flex-1 overflow-y-auto px-2 bg-white">
                @forelse ($keranjang as $key => $item)
                    <div
                        class="flex flex-col gap-2 p-4 mx-2 my-2 bg-white border border-gray-100 rounded-2xl shadow-sm hover:border-emerald-200 hover:shadow-md transition-all group">

                        {{-- Baris 1: Nama & Subtotal --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate pr-2">{{ $item['nama'] }}</p>
                                <p class="text-[11px] font-medium text-gray-400 mt-0.5">Rp
                                    {{ number_format($item['harga'], 0, ',', '.') }} / unit</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-emerald-600">Rp
                                    {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Baris 2: Kontrol Kuantitas --}}
                        <div class="flex items-center justify-between border-t border-gray-50">
                            <p class="text-xs font-medium text-gray-400">Kuantitas:</p>
                            <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-200/60">
                                <button wire:click="kurangiDariKeranjang({{ $key }})"
                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 transition-all shadow-sm active:scale-95">
                                    <x-lucide-minus class="w-4 h-4" />
                                </button>

                                <span
                                    class="text-sm font-black w-10 text-center text-gray-800">{{ $item['qty'] }}</span>

                                <button wire:click="tambahKeKeranjang({{ $key }})"
                                    class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-emerald-600 hover:border-emerald-300 hover:bg-emerald-50 transition-all shadow-sm active:scale-95">
                                    <x-lucide-plus class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center p-6">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <x-lucide-shopping-cart class="w-10 h-10 text-gray-300" />
                        </div>
                        <p class="text-sm font-bold text-gray-700">Keranjang Masih Kosong</p>
                        <p class="text-xs text-gray-400 mt-2 max-w-[200px] leading-relaxed">Silakan pilih produk dari
                            katalog di sebelah kiri untuk mulai transaksi.</p>
                    </div>
                @endforelse
            </div>

            {{-- Area Checkout Bawah --}}
            @if (!empty($keranjang))
                <div class="border-t border-gray-200/80 bg-gray-50/50 p-6 space-y-5">

                    {{-- Ringkasan Total --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total
                                Tagihan</span>
                            <span
                                class="text-xs font-bold text-emerald-600 bg-emerald-50 px-1 rounded-md">{{ $totalItems }}
                                Item</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-medium text-sm">IDR</span>
                            <span class="text-2xl font-black text-gray-900 tracking-tight">
                                {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Pilihan Metode Bayar --}}
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Pilih Metode
                            Pembayaran</p>

                        @php
                            // Cek status role dan status QRIS
                            $isPelanggan = auth()->user()->isPelanggan();

                            // Gunakan properti warung dari user yang sedang login agar selalu akurat
                            $qrisActive = auth()->user()->warung->is_qris_active ?? false;

                            // Jika bukan pelanggan & QRIS aktif, baru kita jadikan 2 kolom
                            $showBoth = !$isPelanggan && $qrisActive;
                        @endphp

                        <div class="grid {{ $showBoth ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">

                            {{-- 1. Tombol Cash (Hanya muncul untuk Owner / Kasir) --}}
                            @if (!$isPelanggan)
                                <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                    class="relative w-full flex flex-col sm:flex-row items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold border-2 transition-all overflow-hidden group
                                    {{ $paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-md shadow-emerald-500/10' : 'border-gray-200 text-gray-500 bg-white hover:border-gray-300 hover:bg-gray-50' }}">
                                    @if ($paymentMethod === 'cash')
                                        <div
                                            class="absolute top-0 right-0 w-6 h-6 bg-emerald-500 rounded-bl-2xl flex items-center justify-center">
                                            <x-lucide-check class="w-3 h-3 text-white ml-1 mb-1" />
                                        </div>
                                    @endif
                                    <x-lucide-banknote
                                        class="w-5 h-5 {{ $paymentMethod === 'cash' ? 'text-emerald-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                                    <span>Tunai (Cash)</span>
                                </button>
                            @endif

                            {{-- 2. Tombol QRIS (Hanya muncul jika warung mengaktifkan QRIS) --}}
                            @if ($qrisActive)
                                <button type="button" wire:click="$set('paymentMethod', 'qris')"
                                    class="relative w-full flex flex-col sm:flex-row items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold border-2 transition-all overflow-hidden group
                                    {{ $paymentMethod === 'qris' ? 'border-blue-500 bg-blue-50 text-blue-700 shadow-md shadow-blue-500/10' : 'border-gray-200 text-gray-500 bg-white hover:border-gray-300 hover:bg-gray-50' }}">
                                    @if ($paymentMethod === 'qris')
                                        <div
                                            class="absolute top-0 right-0 w-6 h-6 bg-blue-500 rounded-bl-2xl flex items-center justify-center">
                                            <x-lucide-check class="w-3 h-3 text-white ml-1 mb-1" />
                                        </div>
                                    @endif
                                    <x-lucide-qr-code
                                        class="w-5 h-5 {{ $paymentMethod === 'qris' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600' }}" />
                                    <span>QRIS Digital</span>
                                </button>
                            @endif

                            {{-- 3. Pesan Peringatan (Jika yang login PELANGGAN namun QRIS sedang OFF) --}}
                            @if ($isPelanggan && !$qrisActive)
                                <div
                                    class="w-full flex flex-col items-center justify-center gap-1.5 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-center">
                                    <x-lucide-store class="w-6 h-6 text-rose-500 mb-1" />
                                    <p class="text-sm font-bold text-rose-800">Pembayaran Digital Nonaktif</p>
                                    <p class="text-xs font-medium text-rose-600 leading-relaxed">
                                        Maaf, toko ini sedang tidak mengaktifkan fitur QRIS. Silakan hubungi kasir atau
                                        pemilik toko.
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Tombol Proses Transaksi --}}
                    <button wire:click="prosesCheckout" wire:loading.attr="disabled"
                        class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 text-white rounded-2xl transition-all shadow-lg active:scale-95 group overflow-hidden relative">
                        <div
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
                        </div>

                        <span wire:loading.remove
                            class="relative flex items-center justify-center gap-2 font-black text-base tracking-wide">
                            @if ($paymentMethod === 'cash')
                                <x-lucide-receipt class="w-5 h-5" /> Proses Pembayaran Tunai
                            @else
                                <x-lucide-scan-line class="w-5 h-5" /> Generate Kode QRIS
                            @endif
                        </span>

                        <span wire:loading
                            class="relative flex items-center justify-center gap-3 font-black text-base tracking-wide">
                            <span class="flex items-center justify-center flex-shrink-0">
                                <svg class="animate-spin h-5 w-5 text-white inline-block align-middle"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>
            @endif

            {{-- MODE 2: TAMPILAN QRIS GENERATED --}}
        @else
            @if ($showQris)
                <div wire:poll.2s="checkPaymentStatus" class="flex-1 flex flex-col bg-gray-50/50">

                    {{-- Konten QR --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-4 text-center animate-fade-in-up">

                        <h4 class="font-black text-xl text-gray-800 tracking-tight">Selesaikan Pembayaran</h4>
                        <p class="text-sm font-medium text-gray-500 mt-1 mb-4">Pindai kode QR di bawah ini menggunakan
                            aplikasi e-Wallet atau M-Banking pelanggan.</p>

                        <div
                            class="bg-white p-3 rounded-3xl shadow-xl shadow-blue-900/10 border-2 border-blue-100 inline-block mb-6 relative group overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-50 pointer-events-none">
                            </div>

                            {{-- Area QR Code --}}
                            <div class="relative bg-white p-2 rounded-xl border border-gray-100">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->margin(1)->style('round')->generate($qrisStringData) !!}
                            </div>
                        </div>

                        <div
                            class="bg-white px-6 py-4 rounded-2xl border border-gray-200 shadow-sm w-full max-w-[180px]">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Tagihan
                                (QRIS)</p>
                            <p class="text-3xl font-black text-blue-600 tracking-tight">
                                <span
                                    class="text-lg font-medium text-blue-400 mr-1">Rp</span>{{ number_format($total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Footer Batal QRIS --}}
                    <div class="p-3 border-t border-gray-200/80 bg-white">
                        <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100 mb-2">
                            <x-lucide-info class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" />
                            <p class="text-[11px] text-amber-800 font-medium leading-relaxed">
                                Jika sudah dibayar mohon konfirmasikan ke petugas kasir. Jika pesanan belum sesuai anda
                                bisa membatalkannya
                            </p>
                        </div>

                        <button wire:click="kosongkanKeranjang"
                            class="w-full py-3 bg-white border-2 border-gray-200 hover:border-rose-300 hover:bg-rose-50 text-gray-600 hover:text-rose-600 font-bold rounded-2xl transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                            <x-lucide-x-circle class="w-5 h-5" /> Batalkan Transaksi
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
