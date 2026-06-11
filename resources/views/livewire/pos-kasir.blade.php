<div class="flex h-[calc(100vh-4rem)] border-t border-gray-200 bg-gray-50">

    {{-- ===== SISI KIRI: Daftar Produk ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden border-r border-gray-200">

        {{-- Header: Search + Filter + Mode Toggle --}}
        <div class="p-4 border-b border-gray-100 bg-white space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk..."
                        class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">

                    <select wire:model.live="filterKategori" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">Semua</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Toggle Mode --}}
                <div
                    class="ml-3 px-3 py-2 border rounded-lg text-xs font-medium transition-colors
                    {{ $mode === 'katalog' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200' }}">
                    {{ $mode === 'klasik' ? '🏪 Mode Katalog' : '⌨️ Mode Kasir' }}
                </div>
            </div>
        </div>

        {{-- Error Message --}}
        @if ($errorMessage)
            <div class="mx-4 mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
                ⚠️ {{ $errorMessage }}
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
                        class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:border-green-400 shadow-md transition-all text-left group
                        {{ $item->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $item->stok <= 0 ? 'disabled' : '' }}>

                        {{-- Foto --}}
                        <div class="{{ $mode === 'katalog' ? 'h-36' : 'h-24' }} bg-gray-50 overflow-hidden">
                            @if ($item->foto)
                                <img src="{{ Storage::url($item->foto) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">🛍️</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                            <p class="text-green-600 font-bold text-sm mt-0.5">
                                Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if ($item->stok <= 0)
                                    <span class="text-red-600 font-bold">Stok Habis ⚠</span>
                                @elseif ($item->isLowStock())
                                    <span class="text-orange-500 font-medium">Stok Menipis ⚠</span>
                                @endif
                            </p>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400">
                        <p class="text-4xl mb-2">📦</p>
                        <p class="text-sm">Tidak ada produk ditemukan</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-4">{{ $produk->links() }}</div>
        </div>
    </div>

    {{-- ===== SISI KANAN: Keranjang + Checkout ===== --}}
    <div class="w-80 lg:w-96 flex flex-col bg-white">

        {{-- Header Keranjang --}}
        <div class="px-4 py-9 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-lg text-gray-800">
                Keranjang
                @if ($totalItems > 0)
                    <span class="ml-1 bg-green-600 text-white text-xs rounded-full px-2 py-0.5">
                        {{ $totalItems }}
                    </span>
                @endif
            </h3>
            @if (!empty($keranjang))
                <button wire:click="kosongkanKeranjang" wire:confirm="Kosongkan keranjang?"
                    class="text-xs text-red-500 hover:text-red-700">
                    Kosongkan
                </button>
            @endif
        </div>

        {{-- ===== MODE NORMAL: Tampilkan Keranjang ===== --}}
        @if (!$qrisDisplayed)

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
                                class="w-7 h-7 bg-gray-100 hover:bg-green-100 rounded-lg text-gray-600 hover:text-green-600 text-lg leading-none transition-colors">
                                +
                            </button>
                        </div>

                        <p class="text-sm font-bold text-gray-800 w-20 text-right">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                        <span class="text-5xl mb-3">🛒</span>
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
                                <button wire:click="$set('paymentMethod', 'cash')"
                                    class="py-2.5 rounded-xl text-sm font-semibold border-2 transition-all
                                {{ $paymentMethod === 'cash'
                                    ? 'border-green-500 bg-green-50 text-green-700'
                                    : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                    💵 Cash
                                </button>
                            @endif
                            <button wire:click="$set('paymentMethod', 'qris')"
                                class="py-2.5 rounded-xl text-sm font-semibold border-2 transition-all
                                {{ $paymentMethod === 'qris'
                                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                                    : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                📱 QRIS
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Proses --}}
                    <button wire:click="prosesCheckout" wire:loading.attr="disabled"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 disabled:bg-green-400
                        text-white font-bold rounded-xl transition-colors shadow-sm">
                        <span wire:loading.remove>
                            {{ $paymentMethod === 'cash' ? '✓ Bayar Cash' : '📱 Generate QRIS' }}
                        </span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            @endif

            {{-- ===== MODE QRIS: Tampilkan QR Code ===== --}}
        @else
            @if ($qrisDisplayed)
                <div wire:poll.3000ms="cekStatusQris" class="hidden"></div>

                <div wire:ignore class="flex-1 flex flex-col items-center justify-center p-6 gap-4">

                    <div class="text-center mb-2">
                        <p class="font-semibold text-gray-800">Scan QR untuk Bayar</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Container QRIS — Midtrans Snap inject QR di sini --}}
                    <div id="snap-container"
                        class="w-full max-w-xs bg-white border-2 border-dashed border-gray-200 rounded-2xl
                            flex items-center justify-center min-h-64">
                        <div class="text-center text-gray-400">
                            <div
                                class="animate-spin w-8 h-8 border-4 border-green-500 border-t-transparent rounded-full mx-auto mb-3">
                            </div>
                            <p class="text-xs">Memuat QRIS...</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 text-center">
                        Menunggu konfirmasi pembayaran...<br>
                        QRIS berlaku selama 15 menit
                    </p>

                    <button wire:click="kosongkanKeranjang" class="text-sm text-red-500 hover:text-red-700 underline">
                        Batalkan Pembayaran
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    (function() {
        if (window._qrisListenerRegistered) return;
        window._qrisListenerRegistered = true;

        document.addEventListener('livewire:init', () => {
            Livewire.on('qris-ready', ({
                token
            }) => {
                loadSnapThenRender(token);
            });
        });

        function loadSnapThenRender(token) {
            if (window.snap) {
                renderQris(token);
                return;
            }

            if (document.querySelector('script[src*="snap.js"]')) {
                setTimeout(() => loadSnapThenRender(token), 10000);
                return;
            }

            const isProduction = {{ config('services.midtrans.is_production') ? 'true' : 'false' }};
            const snapUrl = isProduction ?
                'https://app.midtrans.com/snap/snap.js' :
                'https://app.sandbox.midtrans.com/snap/snap.js';

            const script = document.createElement('script');
            script.src = snapUrl;
            script.setAttribute('data-client-key', '{{ config('services.midtrans.client_key') }}');
            script.onload = () => renderQris(token);
            script.onerror = () => console.error('Gagal memuat Snap.js dari Midtrans');
            document.head.appendChild(script);
        }

        function renderQris(token) {
            const container = document.getElementById('snap-container');
            if (!container) {
                console.error('snap-container tidak ditemukan di DOM');
                return;
            }

            window.snap.embed(token, {
                embedId: 'snap-container',
                onSuccess: () => {
                    /* Polling Livewire yang handle redirect */
                },
                onPending: () => {},
                onError: (result) => console.error('Snap error', result),
            });
        }
    })();
</script>
</div>
</div>
