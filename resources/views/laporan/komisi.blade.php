<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('laporan.index', request()->query()) }}"
                class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-xl border border-transparent hover:border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Komisi Sistem</h2>
                <p class="text-xs text-gray-500 mt-1">Rincian potongan komisi layanan untuk periode yang dipilih.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- KARTU TOTAL KOMISI --}}
            <div
                class="bg-gradient-to-br from-rose-500 to-rose-700 rounded-2xl p-6 text-white shadow-md shadow-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-transform hover:-translate-y-1">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-white/20 rounded-xl">
                            <x-lucide-landmark class="w-6 h-6" />
                        </div>
                        <span
                            class="text-rose-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">
                            Total Potongan Komisi
                        </span>
                    </div>
                    <p class="text-4xl font-black tracking-tight mt-2">
                        Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-left sm:text-right bg-black/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                    <p class="text-xs text-rose-100 font-medium mb-1">Periode Laporan:</p>
                    <p class="text-sm font-bold">{{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}</p>
                    <div class="w-full h-px bg-white/20 my-2"></div>
                    <p class="text-[11px] text-rose-200">Rate Komisi: <span class="font-bold text-white">0.5% /
                            Transaksi</span></p>
                </div>
            </div>

            {{-- TABEL DETAIL KOMISI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-receipt class="w-5 h-5 text-rose-500" /> Rincian Potongan per Nota
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    No. Transaksi</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Waktu Selesai</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Omset Kotor</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Rate</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Nominal Komisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($komisi as $k)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-semibold text-gray-500">
                                        #{{ str_pad($k->transaction_id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-4 px-6 text-xs font-medium text-gray-500">
                                        {{ $k->settled_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-800">
                                        Rp {{ number_format($k->gross_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-right text-gray-400 text-xs font-medium">
                                        <span
                                            class="px-2 py-1 bg-gray-100 rounded-md">{{ number_format($k->commission_rate * 100, 2) }}%</span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-rose-600">
                                        Rp {{ number_format($k->commission_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <x-lucide-folder-open class="w-12 h-12 text-gray-300 mb-3" />
                                            <p class="text-sm font-medium">Tidak ada potongan komisi pada periode ini.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-50 bg-gray-50/30">
                    {{ $komisi->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
