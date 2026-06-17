<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Laporan Keuangan</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Periode: {{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}
                </p>
            </div>
            {{-- Tombol Export --}}
            <div class="flex gap-2">
                <a href="{{ route('laporan.export.pdf', request()->query()) }}"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 flex items-center gap-2">
                    <x-lucide-file-text class="w-4 h-4" /> Export PDF
                </a>
                <a href="{{ route('laporan.export.excel', request()->query()) }}"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 flex items-center gap-2">
                    <x-lucide-file-chart-column class="w-4 h-4" /> Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">
                    {{-- Preset Cepat --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Periode
                            Cepat</label>
                        <select name="preset" onchange="this.form.submit()"
                            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors cursor-pointer font-medium text-gray-700">
                            <option value="hari_ini" {{ request('preset') === 'hari_ini' ? 'selected' : '' }}>Hari Ini
                            </option>
                            <option value="minggu_ini" {{ request('preset') === 'minggu_ini' ? 'selected' : '' }}>Minggu
                                Ini</option>
                            <option value="bulan_ini"
                                {{ request('preset', 'bulan_ini') === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini
                            </option>
                            <option value="bulan_lalu" {{ request('preset') === 'bulan_lalu' ? 'selected' : '' }}>Bulan
                                Lalu</option>
                            <option value="tahun_ini" {{ request('preset') === 'tahun_ini' ? 'selected' : '' }}>Tahun
                                Ini</option>
                        </select>
                    </div>

                    {{-- Custom Range --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dari
                            Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari', $dari->format('Y-m-d')) }}"
                            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sampai
                            Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai', $sampai->format('Y-m-d')) }}"
                            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
                    </div>
                    <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: 3 Kartu Utama (Omset, Net, Laba) --}}
                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Card 1: Omset --}}
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <x-lucide-circle-dollar-sign class="w-6 h-6" />
                            </div>
                            <span
                                class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">Total
                                Omset</span>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100 mb-1 font-medium">Pendapatan Kotor</p>
                            <p class="text-3xl font-black tracking-tight">Rp
                                {{ number_format($laporan['summary']['total_omset'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Card 2: Laba Kotor --}}
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white shadow-md shadow-indigo-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <x-lucide-trending-up class="w-6 h-6" />
                            </div>
                            <span
                                class="text-indigo-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">Laba
                                Kotor</span>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-100 mb-1 font-medium">Keuntungan Penjualan</p>
                            <p class="text-3xl font-black tracking-tight">Rp
                                {{ number_format($laporan['summary']['laba_kotor'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Card 3: Total Net & Komisi (Full Width di bawahnya) --}}
                    <div
                        class="sm:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl"><x-lucide-wallet class="w-5 h-5" />
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Bersih (Net)
                                </p>
                            </div>
                            <p class="text-2xl font-black text-gray-800">Rp
                                {{ number_format($laporan['summary']['total_net'], 0, ',', '.') }}</p>
                        </div>
                        <div class="hidden sm:block w-px h-12 bg-gray-100"></div>
                        <div class="flex-1 w-full">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-rose-50 text-rose-600 rounded-xl"><x-lucide-landmark
                                        class="w-5 h-5" /></div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Potongan Komisi
                                    WARSA</p>
                            </div>
                            <p class="text-2xl font-black text-rose-600">-Rp
                                {{ number_format($laporan['summary']['total_komisi'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Statistik Mini & Metode Bayar --}}
                <div class="space-y-4">
                    {{-- Transaksi & Rata-rata --}}
                    <div
                        class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm grid grid-cols-2 gap-4 divide-x divide-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Total Nota</p>
                            <p class="text-xl font-black text-gray-800">{{ $laporan['summary']['total_transaksi'] }}
                            </p>
                        </div>
                        <div class="pl-4">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Rata-rata/Trx
                            </p>
                            <p class="text-lg font-black text-gray-800">Rp
                                {{ number_format($laporan['summary']['rata_rata_per_trx'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-5">Breakdown
                            Pembayaran</p>
                        <div class="space-y-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl"><x-lucide-hand-coins
                                            class="w-5 h-5" /></div>
                                    <span class="font-bold text-sm text-gray-700">Cash / Tunai</span>
                                </div>
                                <span
                                    class="text-xl font-black text-emerald-600">{{ $laporan['metode_bayar']['cash'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl"><x-lucide-qr-code
                                            class="w-5 h-5" /></div>
                                    <span class="font-bold text-sm text-gray-700">QRIS Digital</span>
                                </div>
                                <span
                                    class="text-xl font-black text-sky-600">{{ $laporan['metode_bayar']['qris'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Chart Omset Harian --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col">
                    <h3 class="flex items-center gap-2 font-bold text-gray-800 mb-6">
                        <x-lucide-bar-chart-3 class="w-5 h-5 text-emerald-500" /> Grafik Omset Harian
                    </h3>
                    <div class="w-full flex-1 relative min-h-[250px]">
                        <canvas id="chartOmset" class="absolute inset-0 w-full h-full"></canvas>
                    </div>
                </div>

                {{-- Produk Terlaris --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
                    <h3 class="flex items-center gap-2 font-bold text-gray-800 mb-4 border-b border-gray-50 pb-4">
                        <x-lucide-trophy class="w-5 h-5 text-yellow-500" /> 10 Produk Terlaris
                    </h3>
                    @if ($laporan['produk_terlaris']->isEmpty())
                        <div class="flex-1 flex flex-col items-center justify-center text-gray-400 py-8">
                            <x-lucide-package-x class="w-12 h-12 mb-3 text-gray-300" />
                            <p class="text-sm font-medium">Belum ada data penjualan.</p>
                        </div>
                    @else
                        <div class="overflow-y-auto flex-1 pr-2" style="max-height: 250px;">
                            <div class="space-y-3">
                                @foreach ($laporan['produk_terlaris'] as $i => $produk)
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100 group">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-xs font-black {{ $i < 3 ? 'text-emerald-600 shadow-sm' : 'text-gray-400' }}">
                                                {{ $i + 1 }}
                                            </div>
                                            <div>
                                                <p
                                                    class="font-bold text-sm text-gray-800 group-hover:text-emerald-700 transition-colors">
                                                    {{ $produk->nama_snapshot }}</p>
                                                <p class="text-xs font-medium text-gray-500 mt-0.5">
                                                    {{ number_format($produk->total_qty) }} unit terjual</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-sm text-emerald-600">Rp
                                                {{ number_format($produk->total_omset, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ================= TABEL DETAIL TRANSAKSI ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-clipboard-list class="w-5 h-5 text-emerald-500" /> Detail Riwayat Transaksi
                    </h3>
                    <a href="{{ route('laporan.komisi', request()->query()) }}"
                        class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">
                        Lihat Laporan Komisi &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    No. Nota</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Waktu</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Petugas</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Omset</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Komisi</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Net (Bersih)</th>
                                <th
                                    class="text-center py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($transaksi as $trx)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="py-4 px-6 font-mono text-xs font-semibold text-gray-500">
                                        #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-4 px-6 text-xs font-medium text-gray-500">
                                        {{ $trx->paid_at->format('d M, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-700">
                                        @if ($trx->kasir)
                                            <div class="flex flex-col">
                                                <span class="text-gray-800 font-bold">{{ $trx->kasir->name }}</span>
                                                <span
                                                    class="text-[10px] font-bold uppercase tracking-wider {{ $trx->kasir->role === 'owner' ? 'text-purple-600' : 'text-blue-600' }}">
                                                    {{ $trx->kasir->role }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 font-medium">Sistem</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-800">
                                        Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-semibold text-rose-500 text-xs">
                                        -Rp {{ number_format($trx->commission_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-600">
                                        Rp {{ number_format($trx->total_net, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $trx->payment_method === 'qris' ? 'bg-sky-100 text-sky-700 border border-sky-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                            {{ $trx->payment_method }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <x-lucide-folder-open class="w-12 h-12 text-gray-300 mb-3" />
                                            <p class="text-sm font-medium">Tidak ada riwayat transaksi pada periode
                                                ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-5 border-t border-gray-50 bg-gray-50/30">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($laporan['transaksi_harian']->pluck('tanggal'));
            const data = @json($laporan['transaksi_harian']->pluck('omset'));

            const ctx = document.getElementById('chartOmset');

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels.map(d => {
                            const date = new Date(d);
                            return date.toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short'
                            });
                        }),
                        datasets: [{
                            label: 'Omset Pendapatan',
                            data: data,
                            backgroundColor: 'rgba(16, 185, 129, 0.2)', // emerald-500 dengan opacity
                            borderColor: '#10b981', // emerald-500 solid
                            borderWidth: 2,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(16, 185, 129, 0.4)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Penting agar tinggi chart bisa dikendalikan oleh div parent
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                padding: 12,
                                titleFont: {
                                    size: 13,
                                    family: "'Figtree', sans-serif"
                                },
                                bodyFont: {
                                    size: 14,
                                    weight: 'bold',
                                    family: "'Figtree', sans-serif"
                                },
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return ' Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                border: {
                                    display: false
                                },
                                grid: {
                                    color: '#f3f4f6',
                                    drawTicks: false,
                                },
                                ticks: {
                                    callback: val => 'Rp ' + val.toLocaleString('id-ID'),
                                    font: {
                                        size: 11,
                                        family: "'Figtree', sans-serif"
                                    },
                                    color: '#6b7280',
                                    padding: 10
                                }
                            },
                            x: {
                                border: {
                                    display: false
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        family: "'Figtree', sans-serif"
                                    },
                                    color: '#6b7280',
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
