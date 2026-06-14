<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Riwayat Transaksi</h2>
            <a href="{{ route('pos.index') }}"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">
                + Transaksi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Hari Ini --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 uppercase font-semibold">Omset Hari Ini</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">
                        Rp {{ number_format($totalOmsetHariIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 uppercase font-semibold">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalTrxHariIni }} nota</p>
                </div>
            </div>

            {{-- Tabel Transaksi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">No</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kasir</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Item</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Total</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Bayar</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($transaksi as $trx)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-mono text-xs text-gray-500">
                                    #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-500">
                                    {{ $trx->paid_at->format('d M, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-gray-700">{{ $trx->kasir->name }}</td>
                                <td class="py-3 px-4 text-gray-500 text-xs">
                                    {{ $trx->items->count() }} item
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-gray-800">
                                    Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-medium uppercase
                                        {{ $trx->payment_method === 'qris' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $trx->payment_method }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('transaksi.struk', $trx) }}"
                                        class="text-xs text-blue-600 hover:underline">
                                        Struk
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-400">
                                    <p class="text-3xl mb-2">🧾</p>
                                    <p class="text-sm">Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100">
                    {{ $transaksi->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
