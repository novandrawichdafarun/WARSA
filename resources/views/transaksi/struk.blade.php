<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaksi->id }}</title>
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

<body class="bg-gray-100 min-h-screen flex items-start justify-center pt-8 p-4">

    <div class="bg-white w-80 rounded-2xl shadow-lg overflow-hidden">

        {{-- Header Struk --}}
        <div class="bg-green-600 text-white text-center py-6 px-4">
            @if ($transaksi->warung->logo)
                <img src="{{ Storage::url($transaksi->warung->logo) }}"
                    class="w-12 h-12 object-cover rounded-full mx-auto mb-2">
            @endif
            <h1 class="font-bold text-lg">{{ $transaksi->warung->nama_warung }}</h1>
            @if ($transaksi->warung->alamat)
                <p class="text-green-200 text-xs mt-1">{{ $transaksi->warung->alamat }}</p>
            @endif
        </div>

        {{-- Info Transaksi --}}
        <div class="px-5 py-4 border-b border-dashed border-gray-200">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>No. Transaksi</span>
                <span
                    class="font-mono font-bold text-gray-800">#{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Tanggal</span>
                <span>{{ $transaksi->paid_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Petugas</span>
                <span>{{ $transaksi->kasir->name }}</span>
            </div>
        </div>

        {{-- Item List --}}
        <div class="px-5 py-4 border-b border-dashed border-gray-200 space-y-2">
            @foreach ($transaksi->items as $item)
                <div class="flex justify-between text-sm">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->nama_snapshot }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $item->quantity }} × Rp {{ number_format($item->harga_snapshot, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="font-semibold text-gray-800">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="px-5 py-4">
            <div class="flex justify-between text-sm text-gray-500 mb-1">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaksi->total_gross, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xl font-bold text-gray-900 border-t border-gray-200 pt-3 mt-2">
                <span>Total Bayar</span>
                <span>Rp {{ number_format($transaksi->total_gross, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>Metode</span>
                <span class="uppercase font-semibold">{{ $transaksi->payment_method }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 text-center py-4 px-5">
            <p class="text-xs text-gray-400">Terima kasih telah berbelanja! 🙏</p>
            <p class="text-xs text-gray-300 mt-1">Powered by WARSA</p>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="no-print fixed bottom-6 left-1/2 -translate-x-1/2 flex gap-3 bg-white shadow-lg rounded-2xl px-6 py-4">
        <button onclick="window.print()"
            class="px-5 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700">
            🖨️ Cetak Struk
        </button>
        <a href="{{ route('pos.index') }}"
            class="px-5 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200">
            Kembali
        </a>
    </div>

</body>

</html>
