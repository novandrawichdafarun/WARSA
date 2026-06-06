<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSummarySheet implements FromArray, WithTitle, WithStyles
{
  public function __construct(protected array $laporan, protected Carbon $dari, protected Carbon $sampai)
  {
  }

  public function title(): string
  {
    return 'Ringkasan';
  }

  public function array(): array
  {
    $s = $this->laporan['summary'];
    $m = $this->laporan['metode_bayar'];

    return [
      ['LAPORAN KEUANGAN WARSA'],
      ['Periode', $this->dari->format('d M Y') . ' — ' . $this->sampai->format('d M Y')],
      ['Diunduh', now()->format('d M Y H:i')],
      [],
      ['RINGKASAN'],
      ['Total Omset', 'Rp ' . number_format($s['total_omset'], 0, ',', '.')],
      ['Total Net (setelah komisi)', 'Rp ' . number_format($s['total_net'], 0, ',', '.')],
      ['Laba Kotor', 'Rp ' . number_format($s['laba_kotor'], 0, ',', '.')],
      ['Komisi WARSA', 'Rp ' . number_format($s['total_komisi'], 0, ',', '.')],
      ['Total Transaksi', $s['total_transaksi'] . ' transaksi'],
      ['Rata-rata per Transaksi', 'Rp ' . number_format($s['rata_rata_per_trx'], 0, ',', '.')],
      [],
      ['METODE PEMBAYARAN'],
      ['Cash', $m['cash'] . ' transaksi'],
      ['QRIS', $m['qris'] . ' transaksi'],
    ];
  }

  public function styles(Worksheet $sheet): array
  {
    return [
      1 => ['font' => ['bold' => true, 'size' => 14]],
      5 => ['font' => ['bold' => true]],
      13 => ['font' => ['bold' => true]],
    ];
  }
}