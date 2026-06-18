<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Komisi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            color: #111;
        }

        .header p {
            margin: 4px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #059669;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .total-row td {
            color: #1f2937;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN PENDAPATAN KOMISI WARSA</h2>
        <p>Periode: <strong>{{ $dari->format('d M Y') }} — {{ $sampai->format('d M Y') }}</strong></p>
        <p>Mitra / Toko: <strong>{{ $namaWarung }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu Selesai</th>
                <th>Toko (Mitra)</th>
                <th>Dicatat Oleh</th>
                <th>Omset Kotor Toko</th>
                <th>Komisi Masuk Sistem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $trx->paid_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->warung ? $trx->warung->nama_warung : '-' }}</td>
                    <td>{{ $trx->kasir ? $trx->kasir->name : '-' }}</td>
                    <td class="text-center">Rp {{ number_format($trx->total_gross, 0, ',', '.') }}</td>
                    <td class="text-center">Rp {{ number_format($trx->commission_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data transaksi pada periode
                        ini.</td>
                </tr>
            @endforelse

            {{-- BARIS TOTAL --}}
            <tr class="total-row">
                <td colspan="4" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-center">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                <td class="text-center">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
