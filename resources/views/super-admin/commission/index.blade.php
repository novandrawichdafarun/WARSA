<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Pendapatan Komisi</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Periode Laporan: <span class="font-bold text-emerald-600">{{ $dari->format('d M Y') }} —
                        {{ $sampai->format('d M Y') }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= FILTER PERIODE ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form method="GET" action="{{ route('super_admin.commission.index') }}"
                    class="flex flex-wrap items-end gap-4">

                    {{-- Preset Cepat --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Periode
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
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Dari
                            Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari', $dari->format('Y-m-d')) }}"
                            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai
                            Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai', $sampai->format('Y-m-d')) }}"
                            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-colors font-medium text-gray-700">
                    </div>

                    <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-sm active:scale-95 flex items-center gap-2">
                        <x-lucide-filter class="w-4 h-4" /> Terapkan Filter
                    </button>
                </form>
            </div>

            {{-- ================= SUMMARY CARDS ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Card 1: Total Komisi --}}
                <div
                    class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 bg-white/20 rounded-xl"><x-lucide-hand-coins class="w-6 h-6" /></div>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-100 mb-1 font-medium">Total Komisi Sistem</p>
                        <p class="text-3xl font-black tracking-tight">Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Card 2: Persentase --}}
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <x-lucide-percent class="w-6 h-6" />
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rate Komisi Aktif
                        </p>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">
                            {{ number_format($persentaseKomisi, 1) }}%</p>
                    </div>
                </div>

                {{-- Card 3: Total Transaksi --}}
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <x-lucide-scroll-text class="w-6 h-6" />
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Eksekusi Nota
                        </p>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $totalTransaksi }} <span
                                class="text-sm font-medium text-gray-400">Trx</span></p>
                    </div>
                </div>

                {{-- Card 4: Rata-Rata --}}
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <x-lucide-chart-no-axes-combined class="w-6 h-6" />
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Rata-rata
                            Pendapatan / Nota</p>
                        <p class="text-2xl font-black text-gray-800 tracking-tight">Rp
                            {{ number_format($rataRata, 0, ',', '.') }}</p>
                    </div>
                </div>

            </div>

            {{-- ================= CHART TREND KOMISI ================= --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-bar-chart-3 class="w-5 h-5 text-emerald-500" /> Tren Pendapatan Komisi Harian
                    </h3>
                </div>
                <div class="w-full flex-1 relative min-h-[300px]">
                    <canvas id="chartKomisi" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

            {{-- ================= TABEL DETAIL TRANSAKSI GLOBAL ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-globe class="w-5 h-5 text-emerald-500" /> Detail Kontribusi Per Warung
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-white border-b border-gray-100">
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Waktu Selesai</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Toko (Mitra)</th>
                                <th
                                    class="text-left py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Dicatat Oleh</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Omset Kotor Toko</th>
                                <th
                                    class="text-right py-4 px-6 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                    Komisi Masuk Sistem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($transaksi as $trx)
                                <tr class="hover:bg-emerald-50/30 transition-colors">
                                    <td class="py-4 px-6 text-xs text-gray-500 font-medium whitespace-nowrap">
                                        {{ $trx->paid_at->format('d M Y, H:i') }}
                                    </td>

                                    <td class="py-4 px-6">
                                        @if ($trx->warung)
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                                    {{ strtoupper(substr($trx->warung->nama_warung, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="font-bold text-gray-800">{{ $trx->warung->nama_warung }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Toko Tidak Diketahui</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6">
                                        @if ($trx->kasir)
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-gray-800 font-bold text-xs">{{ $trx->kasir->name }}</span>
                                                <span
                                                    class="text-[9px] font-black uppercase tracking-wider mt-0.5 {{ $trx->kasir->role === 'owner' ? 'text-blue-600' : 'text-emerald-600' }}">
                                                    {{ $trx->kasir->role }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 font-medium">-</span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-right font-semibold text-gray-600">
                                        Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                    </td>

                                    <td class="py-4 px-6 text-right font-black text-emerald-600 text-base">
                                        +Rp {{ number_format($trx->commission_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-50 rounded-full border border-gray-100 flex items-center justify-center mb-3">
                                                <x-lucide-bar-chart-2 class="w-8 h-8 text-gray-300" />
                                            </div>
                                            <p class="text-sm font-bold text-gray-600">Belum ada transaksi</p>
                                            <p class="text-xs text-gray-400 mt-1">Tidak ada data komisi pada periode
                                                waktu ini.</p>
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

    {{-- ================= SCRIPT CHART ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartKomisi');
            if (ctx) {
                const labels = @json($transaksiHarian->pluck('tanggal'));
                const data = @json($transaksiHarian->pluck('komisi'));

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
                            label: 'Pendapatan Komisi Sistem',
                            data: data,
                            backgroundColor: 'rgba(16, 185, 129, 0.2)', // Emerald-500 dengan opacity
                            borderColor: '#10b981', // Emerald-500 solid
                            borderWidth: 2,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(16, 185, 129, 0.4)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
