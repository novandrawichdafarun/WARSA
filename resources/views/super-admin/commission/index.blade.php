<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800">Laporan Komisi (Super Admin)</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Periode: {{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Periode --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('super_admin.commission.index') }}"
                    class="flex flex-wrap items-end gap-4">
                    {{-- Preset Cepat --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Periode Cepat</label>
                        <select name="preset" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 bg-white">
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
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari', $dari->format('Y-m-d')) }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai', $sampai->format('Y-m-d')) }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Komisi Didapat</p>
                        <span class="text-xl">💸</span>
                    </div>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Omset Global</p>
                        <span class="text-xl">💰</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Transaksi</p>
                        <span class="text-xl">🧾</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">{{ $totalTransaksi }} nota</p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Rata-rata Komisi/Nota</p>
                        <span class="text-xl">📊</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Chart Tren Komisi Harian --}}
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-400 font-semibold uppercase mb-3">Tren Pendapatan Komisi Harian</p>
                <canvas id="chartKomisi" height="80"></canvas>
            </div>

            {{-- Tabel Detail Transaksi Global --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Detail Transaksi & Komisi Seluruh Toko</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Toko
                                    (Warung)</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Petugas</th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Omset
                                    Toko</th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Komisi
                                    (Masuk)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($transaksi as $trx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ $trx->paid_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold">
                                        {{ $trx->warung ? $trx->warung->nama_warung : 'Toko Tidak Diketahui' }}
                                    </td>
                                    <td class="py-3 px-4">
                                      @if($trx->kasir)
                                        <div class="flex flex-col">
                                            <span class="text-gray-800 font-medium">{{ $trx->kasir->name }}</span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $trx->kasir->role === 'owner' ? 'text-purple-600' : 'text-blue-600' }}">
                                                {{ $trx->kasir->role }}
                                            </span>
                                        </div>
                                      @else
                                        <span class="text-gray-400">-</span>
                                      @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-700">
                                        Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-green-600">
                                        +Rp {{ number_format($trx->commission_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-gray-400">
                                        <p class="text-3xl mb-2">📊</p>
                                        <p class="text-sm">Belum ada transaksi pada periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json($transaksiHarian->pluck('tanggal'));
        const data = @json($transaksiHarian->pluck('komisi'));

        new Chart(document.getElementById('chartKomisi'), {
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
                    label: 'Pendapatan Komisi (Rp)',
                    data: data,
                    backgroundColor: 'rgba(22, 163, 74, 0.15)',
                    borderColor: '#16a34a',
                    borderWidth: 2,
                    borderRadius: 4,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: val => 'Rp ' + val.toLocaleString('id-ID'),
                            font: {
                                size: 9
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 9
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
