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
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    📄 Export PDF
                </a>
                <a href="{{ route('laporan.export.excel', request()->query()) }}"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    📊 Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Periode --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">

                    {{-- Preset Cepat --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Periode Cepat</label>
                        <select name="preset" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
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
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai', $sampai->format('Y-m-d')) }}"
                            class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Terapkan Filter
                    </button>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">

                @php
                    $cards = [
                        [
                            'label' => 'Total Omset',
                            'value' => 'Rp ' . number_format($laporan['summary']['total_omset'], 0, ',', '.'),
                            'color' => 'green',
                            'icon' => '💰',
                        ],
                        [
                            'label' => 'Total Net',
                            'value' => 'Rp ' . number_format($laporan['summary']['total_net'], 0, ',', '.'),
                            'color' => 'blue',
                            'icon' => '✅',
                        ],
                        [
                            'label' => 'Laba Kotor',
                            'value' => 'Rp ' . number_format($laporan['summary']['laba_kotor'], 0, ',', '.'),
                            'color' => 'indigo',
                            'icon' => '📈',
                        ],
                        [
                            'label' => 'Komisi SIWARUNG',
                            'value' => 'Rp ' . number_format($laporan['summary']['total_komisi'], 0, ',', '.'),
                            'color' => 'red',
                            'icon' => '💸',
                        ],
                        [
                            'label' => 'Total Transaksi',
                            'value' => $laporan['summary']['total_transaksi'] . ' nota',
                            'color' => 'purple',
                            'icon' => '🧾',
                        ],
                        [
                            'label' => 'Rata-rata / Nota',
                            'value' => 'Rp ' . number_format($laporan['summary']['rata_rata_per_trx'], 0, ',', '.'),
                            'color' => 'orange',
                            'icon' => '📊',
                        ],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">
                                {{ $card['label'] }}
                            </p>
                            <span class="text-xl">{{ $card['icon'] }}</span>
                        </div>
                        <p class="text-xl font-bold text-gray-800">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Metode Pembayaran --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-3">Breakdown Pembayaran</p>
                    <div class="flex items-center justify-between">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $laporan['metode_bayar']['cash'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">💵 Cash</p>
                        </div>
                        <div class="h-12 w-px bg-gray-100"></div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $laporan['metode_bayar']['qris'] }}</p>
                            <p class="text-xs text-gray-400 mt-1">📱 QRIS</p>
                        </div>
                    </div>
                </div>

                {{-- Chart Omset Harian --}}
                <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-3">Omset Harian</p>
                    <canvas id="chartOmset" height="80"></canvas>
                </div>
            </div>

            {{-- Produk Terlaris --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">🏆 10 Produk Terlaris</h3>
                @if ($laporan['produk_terlaris']->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data penjualan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left py-2 text-xs font-semibold text-gray-400 uppercase">#</th>
                                    <th class="text-left py-2 text-xs font-semibold text-gray-400 uppercase">Produk</th>
                                    <th class="text-right py-2 text-xs font-semibold text-gray-400 uppercase">Terjual
                                    </th>
                                    <th class="text-right py-2 text-xs font-semibold text-gray-400 uppercase">Omset</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($laporan['produk_terlaris'] as $i => $produk)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2.5 text-gray-400 font-medium">{{ $i + 1 }}</td>
                                        <td class="py-2.5 font-medium text-gray-800">{{ $produk->nama_snapshot }}</td>
                                        <td class="py-2.5 text-right text-gray-700">
                                            {{ number_format($produk->total_qty) }} unit</td>
                                        <td class="py-2.5 text-right font-semibold text-gray-800">
                                            Rp {{ number_format($produk->total_omset, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Tabel Detail Transaksi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Detail Transaksi</h3>
                    <a href="{{ route('laporan.komisi', request()->query()) }}"
                        class="text-sm text-green-600 hover:underline">
                        Lihat Laporan Komisi →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">No</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Waktu</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Kasir</th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Omset
                                </th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Komisi
                                </th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Net</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase">Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($transaksi as $trx)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 font-mono text-xs text-gray-400">
                                        #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ $trx->paid_at->format('d M, H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ $trx->kasir->name }}</td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-800">
                                        Rp {{ number_format($trx->total_gross, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right text-red-500 text-xs">
                                        -Rp {{ number_format($trx->commission_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-green-600">
                                        Rp {{ number_format($trx->total_net, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="px-2 py-0.5 rounded text-xs font-medium uppercase
                                            {{ $trx->payment_method === 'qris' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $trx->payment_method }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center text-gray-400">
                                        <p class="text-3xl mb-2">📊</p>
                                        <p class="text-sm">Tidak ada transaksi pada periode ini</p>
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
        const labels = @json($laporan['transaksi_harian']->pluck('tanggal'));
        const data = @json($laporan['transaksi_harian']->pluck('omset'));

        new Chart(document.getElementById('chartOmset'), {
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
                    label: 'Omset',
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
