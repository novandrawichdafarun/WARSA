<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.index', request()->query()) }}" class="text-gray-400 hover:text-gray-600">←</a>
            <h2 class="font-semibold text-xl text-gray-800">Laporan Komisi WARSA</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Total Komisi --}}
            <div class="bg-white rounded-xl p-6 border border-red-100 shadow-sm">
                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">
                    Total Komisi Dibayar ke WARSA
                </p>
                <p class="text-3xl font-bold text-red-600">
                    Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Periode: {{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}
                    | Rate: 0.5% per transaksi
                </p>
            </div>

            {{-- Tabel Detail Komisi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Transaksi</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Omset</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Rate</th>
                            <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Komisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($komisi as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-mono text-xs text-gray-400">
                                    #{{ str_pad($k->transaction_id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-500">
                                    {{ $k->settled_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4 text-right text-gray-700">
                                    Rp {{ number_format($k->gross_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right text-gray-400 text-xs">
                                    {{ number_format($k->commission_rate * 100, 2) }}%
                                </td>
                                <td class="py-3 px-4 text-right font-semibold text-red-600">
                                    Rp {{ number_format($k->commission_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    Tidak ada data komisi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100">
                    {{ $komisi->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
