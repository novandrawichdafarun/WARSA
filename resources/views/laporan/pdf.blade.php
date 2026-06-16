<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - {{ $warung->nama_warung }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #374151;
            padding: 30px 40px;
            line-height: 1.5;
        }

        /* Header Styling */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
            margin-bottom: 4px;
        }

        .header-subtitle {
            color: #6b7280;
            font-size: 11px;
        }

        .header-right {
            text-align: right;
        }

        .badge {
            background-color: #ecfdf5;
            color: #047857;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        /* Section Title */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin: 24px 0 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Summary Cards using Table for PDF compatibility */
        .summary-table {
            width: 100%;
            border-spacing: 10px;
            margin: -10px -10px 15px;
            /* Offset spacing */
        }

        .summary-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            width: 33.33%;
        }

        .card-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        .text-emerald {
            color: #059669;
        }

        .text-rose {
            color: #e11d48;
        }

        .text-indigo {
            color: #4f46e5;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background: #f3f4f6;
            color: #4b5563;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }

        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 11px;
            color: #374151;
        }

        .data-table tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        .data-table th.text-right,
        .data-table td.text-right {
            text-align: right;
        }

        .data-table th.text-center,
        .data-table td.text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 10px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>

    {{-- Header Section --}}
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <h1 class="header-title">{{ $warung->nama_warung }}</h1>
                <p class="header-subtitle">Laporan Rekapitulasi Keuangan Terpadu</p>
            </td>
            <td class="header-right" style="vertical-align: middle;">
                <div class="badge">
                    Periode: {{ $laporan['periode']['dari'] }} — {{ $laporan['periode']['sampai'] }}
                </div>
                <p class="header-subtitle" style="margin-top: 6px;">
                    Dicetak pada: {{ now()->format('d M Y, H:i') }}
                </p>
            </td>
        </tr>
    </table>

    {{-- Summary Cards Section --}}
    <p class="section-title">Ringkasan Eksekutif</p>
    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <p class="card-label">Total Omset Kotor</p>
                <p class="card-value text-emerald">Rp
                    {{ number_format($laporan['summary']['total_omset'], 0, ',', '.') }}</p>
            </td>
            <td class="summary-card">
                <p class="card-label">Laba Kotor</p>
                <p class="card-value text-indigo">Rp {{ number_format($laporan['summary']['laba_kotor'], 0, ',', '.') }}
                </p>
            </td>
            <td class="summary-card">
                <p class="card-label">Total Transaksi</p>
                <p class="card-value">{{ $laporan['summary']['total_transaksi'] }} <span
                        style="font-size: 12px; color: #6b7280; font-weight: normal;">Nota</span></p>
            </td>
        </tr>
        <tr>
            <td class="summary-card">
                <p class="card-label">Total Pendapatan Bersih (Net)</p>
                <p class="card-value">Rp {{ number_format($laporan['summary']['total_net'], 0, ',', '.') }}</p>
            </td>
            <td class="summary-card">
                <p class="card-label">Potongan Komisi (0.5%)</p>
                <p class="card-value text-rose">-Rp
                    {{ number_format($laporan['summary']['total_komisi'], 0, ',', '.') }}</p>
            </td>
            <td class="summary-card">
                <p class="card-label">Rata-rata Penjualan / Nota</p>
                <p class="card-value">Rp {{ number_format($laporan['summary']['rata_rata_per_trx'], 0, ',', '.') }}</p>
            </td>
        </tr>
    </table>

    {{-- Top 10 Products Section --}}
    <p class="section-title">10 Produk Paling Laris</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 45%;">Nama Produk / Komoditas</th>
                <th style="width: 20%;" class="text-right">Unit Terjual</th>
                <th style="width: 30%;" class="text-right">Total Omset</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan['produk_terlaris'] as $i => $produk)
                <tr>
                    <td class="text-center" style="color: #9ca3af;">{{ $i + 1 }}</td>
                    <td class="font-bold">{{ $produk->nama_snapshot }}</td>
                    <td class="text-right">{{ number_format($produk->total_qty) }} Unit</td>
                    <td class="text-right font-bold text-emerald">Rp
                        {{ number_format($produk->total_omset, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color:#9ca3af; padding: 20px;">Tidak ada data
                        penjualan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Daily Transactions Section --}}
    @if ($laporan['transaksi_harian']->isNotEmpty())
        <p class="section-title" style="page-break-before: auto;">Rincian Omset Harian</p>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Tanggal Transaksi</th>
                    <th style="width: 30%;" class="text-center">Jumlah Nota</th>
                    <th style="width: 30%;" class="text-right">Total Omset</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan['transaksi_harian'] as $hari)
                    <tr>
                        <td class="font-bold">{{ \Carbon\Carbon::parse($hari->tanggal)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="text-center">{{ $hari->jumlah }} Nota</td>
                        <td class="text-right font-bold text-emerald">Rp {{ number_format($hari->omset, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Footer --}}
    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh <strong>WARSA</strong> (Warung Smart Application).<br>
        Dicetak oleh entitas sistem pada {{ now()->format('Y-m-d H:i:s') }}.
    </div>

</body>

</html>
