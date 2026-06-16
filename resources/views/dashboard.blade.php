<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Utama') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau perkembangan dan performa tokomu hari ini.</p>
            </div>

            {{-- Info Profil Warung --}}
            @if (auth()->user()->warung)
                <div
                    class="bg-white border border-gray-200 shadow-sm rounded-2xl px-4 py-2 flex items-center gap-3 self-start md:self-auto transition-all hover:shadow-md">
                    @if (auth()->user()->warung->logo)
                        <img src="{{ asset('storage/' . auth()->user()->warung->logo) }}" alt="Logo Warung"
                            class="w-10 h-10 rounded-full object-cover border border-gray-100">
                    @else
                        <div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center">
                            <x-lucide-store class="w-5 h-5 text-emerald-500" />
                        </div>
                    @endif
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Warung Aktif</p>
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->warung->nama_warung }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Alert Stok Menipis --}}
        @if (isset($produk_low_stock) && $produk_low_stock_list->isNotEmpty() && !auth()->user()->isSuperAdmin())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mt-6 shadow-sm animate-fade-in-up">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <p class="font-bold text-amber-900 flex items-center gap-2">
                        <span class="p-1.5 bg-amber-100 text-amber-600 rounded-lg"><x-lucide-triangle-alert
                                class="w-4 h-4" /></span>
                        Peringatan: Ada {{ $produk_low_stock }} Produk Menipis!
                    </p>
                    <a href="{{ route('stok.index') }}"
                        class="text-xs font-bold text-amber-700 hover:text-amber-900 bg-amber-100 hover:bg-amber-200 px-3 py-1.5 rounded-lg transition-colors">
                        Kelola Stok &rarr;
                    </a>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($produk_low_stock_list as $produk)
                        <a href="{{ route('produk.edit', $produk) }}"
                            class="px-3 py-1.5 bg-white border border-amber-200 rounded-xl text-xs hover:border-amber-400 hover:shadow-sm transition-all flex items-center gap-2 group">
                            <span
                                class="font-bold text-gray-700 group-hover:text-amber-700">{{ $produk->nama_produk }}</span>
                            @if ($produk->stok <= 0)
                                <span
                                    class="px-2 py-0.5 bg-rose-100 text-rose-700 font-black rounded-md uppercase tracking-wider text-[9px]">Habis</span>
                            @else
                                <span
                                    class="px-2 py-0.5 bg-amber-100 text-amber-700 font-black rounded-md uppercase tracking-wider text-[9px]">Sisa
                                    {{ $produk->stok }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </x-slot>

    {{-- ================= TAMPILAN OWNER ================= --}}
    @if (auth()->user()->isOwner())
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                {{-- Bagian 1: Kartu Statistik Ringkasan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- Card Omset (Dibuat paling menonjol) --}}
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-white/20 rounded-xl">
                                <x-lucide-circle-dollar-sign class="w-6 h-6" />
                            </div>
                            <span
                                class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider bg-white/10 px-3 py-1.5 rounded-full">Hari
                                Ini</span>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100 mb-1 font-medium">Total Omset Penjualan</p>
                            <p class="text-3xl font-black tracking-tight">Rp
                                {{ number_format($omset_hari_ini, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Card Transaksi --}}
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <x-lucide-shopping-bag class="w-6 h-6" />
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Transaksi Hari Ini
                            </p>
                            <p class="text-3xl font-black text-gray-800 tracking-tight">
                                {{ $total_transaksi_hari_ini ?? 0 }} <span
                                    class="text-sm font-medium text-gray-400">Nota</span></p>
                        </div>
                    </div>

                    {{-- Card Total Produk --}}
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <x-lucide-package class="w-6 h-6" />
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Katalog Produk</p>
                            <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $total_produk ?? 0 }} <span
                                    class="text-sm font-medium text-gray-400">Item</span></p>
                        </div>
                    </div>

                    {{-- Card Stok Menipis --}}
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 {{ $produk_low_stock > 0 ? 'bg-rose-50 text-rose-600 animate-pulse' : 'bg-gray-50 text-gray-400' }} rounded-xl">
                                <x-lucide-triangle-alert class="w-6 h-6" />
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Stok Menipis</p>
                            <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $produk_low_stock ?? 0 }}
                                <span class="text-sm font-medium text-gray-400">Produk</span></p>
                        </div>
                    </div>

                </div>

                {{-- Bagian 2: Tombol Aksi Cepat --}}
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-gray-800 mb-5 flex items-center gap-2">
                        <x-lucide-zap class="w-5 h-5 text-amber-500" /> Aksi Cepat Menu
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

                        <a href="{{ route('pos.index') }}"
                            class="p-5 border border-gray-100 rounded-2xl bg-gray-50 hover:bg-emerald-50 hover:border-emerald-200 transition-all flex flex-col items-center text-center gap-3 group hover:-translate-y-1 hover:shadow-md">
                            <span
                                class="p-3 bg-white shadow-sm rounded-xl text-emerald-600 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <x-lucide-monitor class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 group-hover:text-emerald-800">Buka Kasir (POS)
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">Mulai transaksi baru</p>
                            </div>
                        </a>

                        <a href="{{ route('produk.create') }}"
                            class="p-5 border border-gray-100 rounded-2xl bg-gray-50 hover:bg-blue-50 hover:border-blue-200 transition-all flex flex-col items-center text-center gap-3 group hover:-translate-y-1 hover:shadow-md">
                            <span
                                class="p-3 bg-white shadow-sm rounded-xl text-blue-600 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <x-lucide-package-plus class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 group-hover:text-blue-800">Tambah Produk</p>
                                <p class="text-[11px] text-gray-400 mt-1">Input katalog barang baru</p>
                            </div>
                        </a>

                        <a href="{{ route('karyawan.index') }}"
                            class="p-5 border border-gray-100 rounded-2xl bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 transition-all flex flex-col items-center text-center gap-3 group hover:-translate-y-1 hover:shadow-md">
                            <span
                                class="p-3 bg-white shadow-sm rounded-xl text-indigo-600 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <x-lucide-users class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 group-hover:text-indigo-800">Kelola Karyawan
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">Atur akses tim warung</p>
                            </div>
                        </a>

                        <a href="{{ route('pengaturan.index') }}"
                            class="p-5 border border-gray-100 rounded-2xl bg-gray-50 hover:bg-orange-50 hover:border-orange-200 transition-all flex flex-col items-center text-center gap-3 group hover:-translate-y-1 hover:shadow-md">
                            <span
                                class="p-3 bg-white shadow-sm rounded-xl text-orange-600 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all">
                                <x-lucide-settings class="w-7 h-7" />
                            </span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 group-hover:text-orange-800">Pengaturan</p>
                                <p class="text-[11px] text-gray-400 mt-1">Konfigurasi profil warung</p>
                            </div>
                        </a>

                    </div>
                </div>

                {{-- Chart Omset --}}
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <x-lucide-bar-chart-3 class="w-5 h-5 text-emerald-500" /> Pergerakan Omset Bulan Ini
                        </h3>
                        <a href="{{ route('laporan.index') }}"
                            class="text-xs font-bold text-emerald-600 hover:text-emerald-800 hover:underline bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">
                            Lihat Laporan Lengkap &rarr;
                        </a>
                    </div>
                    <div class="w-full flex-1 relative min-h-[300px]">
                        <canvas id="dashboardChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TAMPILAN SUPER ADMIN ================= --}}
    @elseif (auth()->user()->isSuperAdmin())
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                {{-- Banner Welcome --}}
                <div
                    class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-sm p-8 text-white relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="dotPattern2" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="2" cy="2" r="1" fill="currentColor" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#dotPattern2)" />
                        </svg>
                    </div>
                    <div class="relative z-10 flex items-center gap-5">
                        <div
                            class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20">
                            <x-lucide-shield-check class="w-8 h-8 text-emerald-400" />
                        </div>
                        <div>
                            <h3 class="text-2xl font-black mb-1">Sistem Pusat WARSA</h3>
                            <p class="text-gray-300 text-sm font-medium">Selamat Datang, Administrator
                                {{ auth()->user()->name }}. Berikut ringkasan ekosistem hari ini.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-md shadow-emerald-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-white/20 rounded-xl"><x-lucide-store class="w-6 h-6" /></div>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-100 mb-1 font-medium">Mitra Warung Aktif</p>
                            <p class="text-3xl font-black tracking-tight">{{ $totalWarung ?? 0 }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white shadow-md shadow-indigo-200 flex flex-col justify-between transition-transform hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-white/20 rounded-xl"><x-lucide-users class="w-6 h-6" /></div>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-100 mb-1 font-medium">Total Pengguna (User)</p>
                            <p class="text-3xl font-black tracking-tight">{{ $totalUser ?? 0 }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <x-lucide-circle-dollar-sign class="w-6 h-6" />
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Global Omset Hari
                                Ini</p>
                            <p class="text-2xl font-black text-gray-800 tracking-tight">Rp
                                {{ number_format($omset_hari_ini, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between transition-transform hover:-translate-y-1 group">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <x-lucide-shopping-bag class="w-6 h-6" />
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Global Transaksi
                                Hari Ini</p>
                            <p class="text-2xl font-black text-gray-800 tracking-tight">
                                {{ $total_transaksi_hari_ini ?? 0 }} <span
                                    class="text-sm font-medium text-gray-400">Nota</span></p>
                        </div>
                    </div>
                </div>

                {{-- Chart Super Admin --}}
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <x-lucide-globe class="w-5 h-5 text-emerald-500" /> Pergerakan Omset Ekosistem (30 Hari)
                        </h3>
                    </div>
                    <div class="w-full flex-1 relative min-h-[300px]">
                        <canvas id="dashboardChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= SCRIPT CHART ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardChart');

            if (ctx) {
                const labels = @json(collect($chart_harian)->pluck('tanggal'));
                const data = @json(collect($chart_harian)->pluck('omset'));

                // Buat gradient hijau untuk area di bawah garis
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Emerald-500 tebal
                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)'); // Emerald-500 transparan

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels.map(d => new Date(d).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short'
                        })),
                        datasets: [{
                            label: 'Omset Penjualan',
                            data: data,
                            borderColor: '#059669', // Emerald-600
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4, // Membuat garis melengkung (smooth)
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#059669',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
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
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return ' Rp ' + context.raw.toLocaleString('id-ID');
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
                                    color: '#9ca3af',
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
                                    color: '#9ca3af',
                                    padding: 10,
                                    maxTicksLimit: 8
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            }
        });
    </script>
</x-app-layout>
