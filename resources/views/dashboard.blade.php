<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Utama') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau perkembangan dan performa tokomu hari ini.</p>
            </div>
            @if (auth()->user()->warung)
                <div
                    class="bg-green-50 border border-green-200 rounded-xl px-4 py-2 flex items-center gap-2 self-start md:self-auto">
                    @if (auth()->user()->warung->logo)
                        <img src="{{ asset('storage/' . auth()->user()->warung->logo) }}" alt="Logo Warung"
                            class="w-10 h-10 rounded-full object-cover">
                    @else
                        <span class="text-xl">🏪</span>
                    @endif
                    <div>
                        <p class="text-xs text-green-600 font-medium">Nama Warung</p>
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->warung->nama_warung }}</p>
                    </div>
                </div>
            @endif
        </div>
        {{-- Alert Stok Menipis — tampil hanya jika ada --}}
        @if (isset($produk_low_stock) && $produk_low_stock_list->isNotEmpty() && !auth()->user()->isSuperAdmin())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mt-3">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-amber-800 flex items-center gap-2">
                        ⚠️ Stok Menipis ({{ $produk_low_stock }} produk)
                    </p>
                    <a href="{{ route('stok.index') }}" class="text-xs text-amber-600 hover:text-amber-800 underline">
                        Lihat semua →
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($produk_low_stock_list as $produk)
                        <a href="{{ route('produk.edit', $produk) }}"
                            class="px-3 py-1.5 bg-white border border-amber-200 rounded-lg text-xs
                            hover:border-amber-400 transition-colors flex items-center gap-2">
                            <span class="font-medium">{{ $produk->nama_produk }}</span>
                            <span class="text-red-600 font-bold">{{ $produk->stok }} sisa</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </x-slot>

    @if (auth()->user()->isOwner())
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                {{-- Bagian 1: Kartu Statistik Ringkasan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center border border-green-100">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Omset Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($omset_hari_ini, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center border border-blue-100">
                            <span class="text-2xl">🛒</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi Hari
                                Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $total_transaksi_hari_ini ?? 0 }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center border border-purple-100">
                            <span class="text-2xl">📦</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Produk</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $total_produk ?? 0 }} <span
                                    class="text-sm font-normal text-gray-500">Item</span></p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14{{ $produk_low_stock > 0 ? ' bg-red-100 border-red-100 animate-pulse' : 'bg-gray-100 border-gray-100' }} rounded-full flex items-center justify-center border ">
                            <span class="text-2xl">⚠️</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Menipis</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $produk_low_stock ?? 0 }} <span
                                    class="text-sm font-normal text-gray-500">Item</span></p>
                        </div>
                    </div>

                </div>

                {{-- Bagian 2: Tombol Aksi Cepat (UX Improvement) --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat Menu Manajemen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('pos.index') }}"
                            class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                            <span
                                class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">🖥️</span>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Buka Kasir (POS)</p>
                                <p class="text-xs text-gray-400">Mulai transaksi baru</p>
                            </div>
                        </a>

                        <a href="{{ route('produk.create') }}"
                            class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                            <span
                                class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">➕</span>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Tambah Produk</p>
                                <p class="text-xs text-gray-400">Input produk baru</p>
                            </div>
                        </a>

                        <a href="{{ route('karyawan.index') }}"
                            class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                            <span
                                class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">👥</span>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Kelola Karyawan</p>
                                <p class="text-xs text-gray-400">Manajemen hak akses</p>
                            </div>
                        </a>

                        <a href="{{ route('pengaturan.index') }}"
                            class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all flex items-center gap-3 group">
                            <span
                                class="text-2xl p-2 bg-white shadow-sm rounded-lg group-hover:scale-110 transition-transform">⚙️</span>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Pengaturan</p>
                                <p class="text-xs text-gray-400">Konfigurasi profile warung</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Chart Omset 30 hari terakhir --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800">Omset Bulan Ini</h3>
                        <a href="{{ route('laporan.index') }}" class="text-xs text-green-600 hover:underline">
                            Lihat laporan lengkap →
                        </a>
                    </div>
                    <canvas id="dashboardChart" height="80"></canvas>
                </div>
            </div>
        </div>
    @elseif (auth()->user()->isSuperAdmin())
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div
                    class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang,
                        {{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">Berikut adalah ringkasan data aplikasi kamu hari
                        ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center border border-green-100">
                            <span class="text-2xl">🏪</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total
                                Warung</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalWarung ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center border border-blue-100">
                            <span class="text-2xl">👥</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total
                                User</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalUser ?? 0 }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center border border-green-100">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Omset Hari Ini
                            </p>
                            <p class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($omset_hari_ini, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-all">
                        <div
                            class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center border border-blue-100">
                            <span class="text-2xl">🛒</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Transaksi Hari
                                Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $total_transaksi_hari_ini ?? 0 }}
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Chart Omset 30 hari terakhir --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800">Omset Bulan Ini</h3>
                        <a href="{{ route('laporan.index') }}" class="text-xs text-green-600 hover:underline">
                            Lihat laporan lengkap →
                        </a>
                    </div>
                    <canvas id="dashboardChart" height="80"></canvas>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels = @json(collect($chart_harian)->pluck('tanggal'));
        const data = @json(collect($chart_harian)->pluck('omset'));

        new Chart(document.getElementById('dashboardChart'), {
            type: 'line',
            data: {
                labels: labels.map(d => new Date(d).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short'
                })),
                datasets: [{
                    label: 'Omset',
                    data: data,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
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
