<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaksi->id }}</title>
    {{-- Icon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 12px;
            }
        }
    </style>
</head>

<body
    class="bg-gray-100 min-h-screen flex flex-col items-center py-10 px-4 font-sans text-gray-900 selection:bg-emerald-200">

    {{-- Kertas Struk (Print Area) --}}
    <div
        class="print-area bg-white w-full max-w-[340px] rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden relative">

        {{-- Header Struk --}}
        <div class="pt-8 pb-4 px-6 text-center">
            <h1 class="font-black text-xl uppercase tracking-tight">{{ $transaksi->warung->nama_warung }}</h1>

            @if ($transaksi->warung->alamat)
                <p class="text-xs text-gray-500 mt-1.5 px-2 leading-snug">{{ $transaksi->warung->alamat }}</p>
            @endif

            @if ($transaksi->warung->telepon)
                <p class="text-xs text-gray-500 mt-0.5">Telp: {{ $transaksi->warung->telepon }}</p>
            @endif
        </div>

        {{-- Info Transaksi --}}
        <div class="px-6 pb-4 border-b border-dashed border-gray-300">
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>No. Nota:</span>
                <span class="font-mono font-bold">#{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-600 mb-1">
                <span>Waktu:</span>
                <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-600">
                <span>Kasir:</span>
                <span class="font-semibold">{{ $transaksi->kasir->name ?? 'Sistem' }}</span>
            </div>
        </div>

        {{-- Daftar Item Belanjaan --}}
        <div class="px-6 py-4 space-y-3 border-b border-dashed border-gray-300">
            @foreach ($transaksi->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div class="pr-2">
                        <p class="font-bold text-gray-800">{{ $item->nama_snapshot }}</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ $item->qty }}x @ Rp
                            {{ number_format($item->harga_snapshot, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Total & Metode Bayar --}}
        <div class="px-6 py-4 border-b border-dashed border-gray-300">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Subtotal Item</span>
                <span>Rp {{ number_format($transaksi->total_gross, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-end mt-3">
                <span class="text-sm font-bold text-gray-800 uppercase tracking-wider">Total Tagihan</span>
                <span class="text-xl font-black text-gray-900 tracking-tight">Rp
                    {{ number_format($transaksi->total_gross, 0, ',', '.') }}</span>
            </div>

            <div class="mt-4 p-3 bg-gray-50 rounded-xl flex justify-between items-center text-xs">
                <span class="text-gray-500 font-semibold uppercase tracking-wider">Metode Bayar</span>
                <span
                    class="font-black px-2 py-1 rounded border border-gray-200 bg-white
                    {{ $transaksi->payment_method === 'qris' ? 'text-blue-600' : 'text-emerald-600' }}">
                    {{ strtoupper($transaksi->payment_method) }}
                </span>
            </div>

            @if ($transaksi->payment_status !== 'paid')
                <div class="mt-2 text-center text-xs font-bold text-rose-600 animate-pulse">
                    *BELUM LUNAS / PENDING
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center py-6 px-6">
            <p class="text-sm font-bold text-gray-800">Terima kasih telah berbelanja!</p>
            <p class="text-[10px] text-gray-400 mt-1">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            <div class="mt-4 flex gap-1 items-center justify-center opacity-50 grayscale">
                <p class="text-[9px] font-bold tracking-widest text-gray-500 uppercase">Powered by WARSA</p>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi Floating (Melayang di bawah layar saat di web) --}}
    <div class="no-print fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
        <div
            class="flex items-center gap-3 bg-white/90 backdrop-blur-md shadow-2xl rounded-full p-2.5 border border-gray-200/50">

            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('transaksi.riwayat') }}"
                class="flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-full transition-colors"
                title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>

            <button onclick="window.print()"
                class="flex items-center gap-2 px-6 h-12 bg-emerald-600 text-white rounded-full text-sm font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/30 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak Struk
            </button>

            @if (auth()->user()->isKasir() || auth()->user()->isOwner())
                <a href="{{ route('pos.index') }}"
                    class="flex items-center gap-2 px-6 h-12 bg-gray-900 text-white rounded-full text-sm font-bold hover:bg-black transition-all shadow-lg active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Trx Baru
                </a>
            @endif

        </div>
    </div>

</body>

</html>
