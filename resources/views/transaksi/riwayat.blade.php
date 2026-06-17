<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    Riwayat Transaksi
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau seluruh catatan penjualan dan status pembayaran warung Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('pos.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:shadow-md transform active:scale-95">
                    <x-lucide-monitor-play class="w-5 h-5" /> Buka Layar Kasir
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-up">
                    <div class="p-1 bg-emerald-500 rounded-full text-white flex-shrink-0">
                        <x-lucide-check class="w-4 h-4" />
                    </div>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Summary Cards Hari Ini --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Card Omset --}}
                <div
                    class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex items-center justify-between transition-transform hover:-translate-y-1">
                    <div>
                        <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-1">Omset Penjualan
                            Hari Ini</p>
                        <p class="text-3xl font-black tracking-tight">Rp
                            {{ number_format($totalOmsetHariIni, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <x-lucide-circle-dollar-sign class="w-8 h-8 text-white" />
                    </div>
                </div>

                {{-- Card Total Transaksi --}}
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-md shadow-blue-200 flex items-center justify-between transition-transform hover:-translate-y-1">
                    <div>
                        <p class="text-blue-100 text-[10px] font-bold uppercase tracking-wider mb-1">Total Transaksi
                            Hari Ini</p>
                        <p class="text-3xl font-black tracking-tight">{{ $totalTrxHariIni }} <span
                                class="text-lg font-medium text-blue-200">Nota</span></p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <x-lucide-receipt class="w-8 h-8 text-white" />
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat Transaksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-clipboard-list class="w-5 h-5 text-emerald-500" /> Daftar Transaksi Terkini
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100">
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    No. Nota</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Waktu Transaksi</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Petugas</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Total Tagihan</th>
                                <th
                                    class="text-center py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Metode & Status</th>
                                <th
                                    class="text-center py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($transaksi as $trx)
                                <tr class="hover:bg-emerald-50/30 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-gray-600">
                                        #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500 font-medium">
                                        {{ $trx->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($trx->kasir)
                                            <p class="font-bold text-gray-800 text-sm">{{ $trx->kasir->name }}</p>
                                            <p
                                                class="text-[10px] font-bold uppercase tracking-wider mt-0.5
                                                {{ $trx->kasir->role === 'owner' ? 'text-purple-600' : 'text-blue-600' }}">
                                                {{ $trx->kasir->role }}
                                            </p>
                                        @else
                                            <span class="text-gray-400 font-medium italic">Sistem</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-gray-800 text-base">
                                        Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            {{-- Badge Metode Pembayaran --}}
                                            <span
                                                class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md border
                                                {{ $trx->payment_method === 'qris' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                                {{ $trx->payment_method }}
                                            </span>

                                            {{-- Badge Status --}}
                                            @if ($trx->payment_status === 'paid')
                                                <span
                                                    class="flex items-center gap-1 text-[10px] font-bold text-emerald-600">
                                                    <x-lucide-check-circle-2 class="w-3.5 h-3.5" /> LUNAS
                                                </span>
                                            @elseif ($trx->payment_status === 'cancelled')
                                                <span
                                                    class="flex items-center gap-1 text-[10px] font-bold text-rose-600">
                                                    <x-lucide-x-circle class="w-3.5 h-3.5" /> DIBATALKAN
                                                </span>
                                            @else
                                                <span
                                                    class="flex items-center gap-1 text-[10px] font-bold text-amber-600 animate-pulse">
                                                    <x-lucide-clock class="w-3.5 h-3.5" /> PENDING
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            {{-- Tombol Konfirmasi QRIS Khusus Status Pending --}}
                                            @if ($trx->payment_status === 'pending' && $trx->payment_method === 'qris')
                                                <button x-data=""
                                                    x-on:click.prevent="$dispatch('open-modal', 'transaksi-confirm-{{ $trx->id }}')"
                                                    class="inline-flex gap-1 items-center justify-center w-28 h-9 bg-white border border-gray-200 hover:border-emerald-300 text-emerald-500 hover:bg-emerald-50 rounded-xl shadow-sm transition-all">
                                                    <x-lucide-check-check class="w-4 h-4" /> Konfirmasi
                                                </button>
                                            @endif

                                            {{-- Tombol Lihat Struk --}}
                                            @if ($trx->payment_status === 'paid')
                                                <a href="{{ route('transaksi.struk', $trx) }}"
                                                    class="inline-flex gap-1 items-center justify-center w-28 h-9 bg-white border border-gray-200 hover:border-emerald-300 text-emerald-500 hover:bg-emerald-50 rounded-xl shadow-sm transition-all">
                                                    <x-lucide-receipt-text class="w-4 h-4" /> Cetak Struk
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <x-modal name="transaksi-confirm-{{ $trx->id }}" focusable>
                                    <form action="{{ route('transaksi.updateStatus', $trx) }}" method="POST"
                                        class="p-6 text-center">
                                        @csrf
                                        @method('PATCH')
                                        <div
                                            class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <x-lucide-receipt stroke-width="2" class="w-8 h-8 text-emerald-500" />
                                        </div>
                                        <h2 class="text-xl font-bold text-gray-900 mb-2">Apakah pelanggan sudah
                                            menunjukkan bukti pembayaran QRIS yang valid?</h2>
                                        <p class="text-sm text-gray-500 mb-6">
                                            Anda akan mengkonfirmasi pembayaran untuk transaksi
                                            <strong>#{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</strong> <br>
                                            Total Pembayaran <strong>Rp
                                                {{ number_format($trx->total_gross, 0, ',', '.') }}</strong>
                                        </p>

                                        <div class="flex justify-center gap-3">
                                            <button type="button" x-on:click="$dispatch('close')"
                                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                                                Ya, Konfirmasi Pembayaran
                                            </button>
                                        </div>
                                    </form>
                                </x-modal>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mb-3">
                                                <x-lucide-receipt class="w-8 h-8 text-gray-300" />
                                            </div>
                                            <p class="text-sm font-bold text-gray-600">Belum ada transaksi</p>
                                            <p class="text-xs text-gray-400 mt-1">Lakukan penjualan di POS Kasir
                                                terlebih dahulu.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($transaksi->hasPages())
                    <div class="p-5 border-t border-gray-50 bg-gray-50/30">
                        {{ $transaksi->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
