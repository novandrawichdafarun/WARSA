<div class="flex h-[calc(100vh-4rem)] border-t border-gray-200 bg-gray-50">
    <div>
        <div x-data="{ tampil: false, pesan: '' }"
            @tampilkan-alert.window="
            tampil = true; 
            pesan = $event.detail.pesan; 
            setTimeout(() => tampil = false, 5000)
        ">
            <div x-show="tampil" style="display: none;" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-8"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-8"
                class="fixed top-5 right-5 z-50 bg-emerald-500 text-white px-6 py-4 rounded-lg shadow-xl border-l-4 border-emerald-700 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-bounce" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <div>
                    <p class="font-bold">Pesanan Baru!</p>
                    <p class="text-sm" x-text="pesan"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SISI KIRI: Daftar Produk ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden border-r border-gray-200">

        {{-- Header: Search + Filter + Mode Toggle --}}
        <div class="p-4 border-b border-gray-100 bg-white space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk..."
                        class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">

                    <select wire:model.live="filterKategori"
                        class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">Semua</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Error Message --}}
        @if ($errorMessage)
            <div class="mx-4 mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                <x-lucide-triangle-alert stroke-width="2" class="w-4 h-4" /> {{ $errorMessage }}
                <button wire:click="$set('errorMessage', null)" class="float-right">×</button>
            </div>
        @endif

        {{-- Grid Produk — berbeda layout tergantung mode --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div
                class="{{ $mode === 'katalog'
                    ? 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4'
                    : 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3' }}">

                @forelse ($produk as $item)
                    <button wire:click="tambahKeKeranjang({{ $item->id }})"
                        class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-emerald-400 shadow-md transition-all text-left group
                        {{ $item->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $item->stok <= 0 ? 'disabled' : '' }}>

                        {{-- Foto --}}
                        <div class="{{ $mode === 'katalog' ? 'h-36' : 'h-24' }} bg-gray-50 overflow-hidden">
                            @if ($item->foto)
                                <img src="{{ Storage::url($item->foto) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">
                                    <x-lucide-shopping-bag class="w-12 h-12 text-emerald-500" />
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                            <p class="text-emerald-600 font-bold text-sm mt-0.5">
                                Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if ($item->stok <= 0)
                                    <span class="flex gap-1 text-red-600 font-bold">Stok Habis <x-lucide-triangle-alert
                                            class="w-3 h-3 mt-0.5" /></span>
                                @elseif ($item->isLowStock())
                                    <span class="flex gap-1 text-orange-500 font-medium">Stok Menipis
                                        <x-lucide-triangle-alert class="w-3 h-3 mt-0.5" /></span>
                                @endif
                            </p>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400">
                        <p class="text-4xl mb-2"><x-lucide-package class="w-8 h-8 text-emerald-500" /></p>
                        <p class="text-sm">Tidak ada produk yang dijual</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4">{{ $produk->links() }}</div>
        </div>
    </div>

    {{-- ===== SISI KANAN: Keranjang + Checkout ===== --}}
    <div class="w-64 md:w-72 lg:w-80 flex flex-col bg-white">

        {{-- Header Keranjang --}}
        <div class="px-4 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-lg text-gray-800">
                Keranjang
                @if ($totalItems > 0)
                    <span class="ml-1 bg-emerald-600 text-white text-xs rounded-full px-2 py-0.5">
                        {{ $totalItems }}
                    </span>
                @endif
            </h3>
            @if (!empty($keranjang) && !$showQris)
                <button wire:click="kosongkanKeranjang" wire:confirm="Kosongkan keranjang?"
                    class="text-xs text-red-500 hover:text-red-700">
                    Kosongkan
                </button>
            @endif
        </div>

        {{-- ===== MODE NORMAL: Tampilkan Keranjang ===== --}}
        @if (!$showQris)

            {{-- List Item Keranjang --}}
            <div class="flex-1 overflow-y-auto">
                @forelse ($keranjang as $key => $item)
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item['nama'] }}</p>
                            <p class="text-xs text-gray-400">
                                Rp {{ number_format($item['harga'], 0, ',', '.') }} × {{ $item['qty'] }}
                            </p>
                        </div>

                        {{-- Qty Controls --}}
                        <div class="flex items-center gap-2">
                            <button wire:click="kurangiDariKeranjang({{ $key }})"
                                class="w-7 h-7 bg-gray-100 hover:bg-red-100 rounded-lg text-gray-600 hover:text-red-600 text-lg leading-none transition-colors">
                                −
                            </button>
                            <span class="text-sm font-bold w-6 text-center">{{ $item['qty'] }}</span>
                            <button wire:click="tambahKeKeranjang({{ $key }})"
                                class="w-7 h-7 bg-gray-100 hover:bg-emerald-100 rounded-lg text-gray-600 hover:text-emerald-600 text-lg leading-none transition-colors">
                                +
                            </button>
                        </div>

                        <p class="text-sm font-bold text-gray-800 w-20 text-right">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                        <span class="text-5xl mb-3"><x-lucide-shopping-cart class="w-14 h-14 text-emerald-500" /></span>
                        <p class="text-sm">Keranjang kosong</p>
                        <p class="text-xs mt-1">Pilih produk dari kiri</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer Checkout --}}
            @if (!empty($keranjang))
                <div class="border-t border-gray-100 p-4 space-y-4">

                    {{-- Total --}}
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-700">Total</span>
                        <span class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Pilih Metode Bayar --}}
                    <div>
                        <p class="text-xs text-gray-500 mb-2 font-medium">Metode Pembayaran</p>
                        <div class="grid {{ !auth()->user()->isPelanggan() ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
                            @if (!auth()->user()->isPelanggan())
                                <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                    aria-pressed="{{ $paymentMethod === 'cash' ? 'true' : 'false' }}"
                                    class="w-full inline-flex items-center justify-center gap-2 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold border-2 transition-all focus:outline-none
                    {{ $paymentMethod === 'cash'
                        ? 'border-emerald-500 bg-emerald-50 text-emerald-700'
                        : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                    <x-lucide-hand-coins class="w-5 h-5" /> <span>Cash</span>
                                </button>
                            @endif

                            <button type="button" wire:click="$set('paymentMethod', 'qris')"
                                aria-pressed="{{ $paymentMethod === 'qris' ? 'true' : 'false' }}"
                                class="w-full inline-flex items-center justify-center gap-2 py-2.5 min-h-[44px] rounded-xl text-sm font-semibold border-2 transition-all focus:outline-none
                {{ $paymentMethod === 'qris'
                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                    : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <x-lucide-qr-code class="w-5 h-5" /> <span>QRIS</span>
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Proses --}}
                    <button wire:click="prosesCheckout" wire:loading.attr="disabled"
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center">
                        <span wire:loading.remove class="flex items-center justify-center gap-1">
                            @if ($paymentMethod === 'cash')
                                <x-lucide-hand-coins class="w-6 h-6" />
                                <span class="text-base">Bayar Cash</span>
                            @else
                                <x-lucide-qr-code class="w-6 h-6" />
                                <span class="text-base">Generate QRIS</span>
                            @endif
                        </span>
                        <span wire:loading class="flex items-center justify-center">Memproses...</span>
                    </button>
                </div>
            @endif

            {{-- ===== MODE QRIS: Tampilkan QR Code ===== --}}
        @else
            @if ($showQris)
                <div class="flex-1 flex flex-col items-center justify-center p-4 gap-2">

                    <div class="text-center">
                        <p class="font-semibold text-gray-800">Scan QR untuk Bayar</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Container QRIS — Midtrans Snap inject QR di sini --}}
                    <div
                        class="w-full max-w-72 bg-white border-2 border-dashed border-gray-200 rounded-2xl
                            flex items-center justify-center min-h-72">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($qrisStringData) !!}
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        *Mohon masukkan nominal secara manual jika aplikasi tidak mendeteksinya secara otomatis.
                    </p>

                    <button wire:click="kosongkanKeranjang" class="text-sm text-red-500 hover:text-red-700 underline">
                        Batalkan Pembayaran
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>
