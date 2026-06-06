<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1f2937;
            padding: 20px;
        }

        h1 {
            font-size: 18px;
            color: #15803d;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 4px;
            margin: 16px 0 10px;
        }

        .grid-2 {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 16px;
        }

        .card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 8px;
        }

        .card-label {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .card-value {
            font-size: 16px;
            font-weight: bold;
            color: #15803d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #15803d;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10px;
        }

        tr:nth-child(even) td {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <h1>{{ $warung->nama_warung }}</h1>
    <p class="subtitle">
        Laporan Keuangan | {{ $laporan['periode']['dari'] }} — {{ $laporan['periode']['sampai'] }}
        | Dicetak: {{ now()->format('d M Y H:i') }}
    </p>

    {{-- Summary Cards --}}
    <p class="section-title">Ringkasan</p>
    <div class="grid-2">
        <div class="col">
            <div class="card">
                <p class="card-label">Total Omset</p>
                <p class="card-value">Rp {{ number_format($laporan['summary']['total_omset'], 0, ',', '.') }}</p>
            </div>
            <div class="card">
                <p class="card-label">Laba Kotor</p>
                <p class="card-value" style="color: #1d4ed8">
                    Rp {{ number_format($laporan['summary']['laba_kotor'], 0, ',', '.') }}
                </p>
            </div>
            <div class="card">
                <p class="card-label">Total Transaksi</p>
                <p class="card-value" style="color: #374151">
                    {{ $laporan['summary']['total_transaksi'] }} nota
                </p>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <p class="card-label">Total Net (setelah komisi)</p>
                <p class="card-value">
                    Rp {{ number_format($laporan['summary']['total_net'], 0, ',', '.') }}
                </p>
            </div>
            <div class="card">
                <p class="card-label">Komisi WARSA (0.5%)</p>
                <p class="card-value" style="color: #dc2626">
                    Rp {{ number_format($laporan['summary']['total_komisi'], 0, ',', '.') }}
                </p>
            </div>
            <div class="card">
                <p class="card-label">Rata-rata per Transaksi</p>
                <p class="card-value" style="color: #374151">
                    Rp {{ number_format($laporan['summary']['rata_rata_per_trx'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Produk Terlaris --}}
    <p class="section-title">10 Produk Terlaris</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th class="text-right">Terjual (unit)</th>
                <th class="text-right">Omset</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan['produk_terlaris'] as $i => $produk)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $produk->nama_snapshot }}</td>
                    <td class="text-right">{{ number_format($produk->total_qty) }}</td>
                    <td class="text-right">Rp {{ number_format($produk->total_omset, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#9ca3af">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Transaksi Harian --}}
    @if ($laporan['transaksi_harian']->isNotEmpty())
        <p class="section-title">Omset Harian</p>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-right">Jumlah Transaksi</th>
                    <th class="text-right">Total Omset</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan['transaksi_harian'] as $hari)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($hari->tanggal)->translatedFormat('l, d M Y') }}</td>
                        <td class="text-right">{{ $hari->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($hari->omset, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dokumen ini digenerate otomatis oleh WARSA — Sistem Informasi Manajemen Warung Digital
    </div>

</body>

</html>
