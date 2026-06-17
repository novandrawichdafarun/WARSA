<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanSummarySheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize
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
      ['Periode Laporan', $this->dari->format('d M Y') . ' — ' . $this->sampai->format('d M Y')],
      ['Dicetak Pada', now()->format('d M Y, H:i')],
      [],
      ['RINGKASAN FINANSIAL', 'NILAI'], // Menambahkan header 'NILAI' untuk kerapian
      ['Total Omset Kotor', 'Rp ' . number_format($s['total_omset'], 0, ',', '.')],
      ['Total Bersih (Net)', 'Rp ' . number_format($s['total_net'], 0, ',', '.')],
      ['Total Laba Kotor', 'Rp ' . number_format($s['laba_kotor'], 0, ',', '.')],
      ['Potongan Komisi WARSA', '- Rp ' . number_format($s['total_komisi'], 0, ',', '.')],
      ['Total Eksekusi Transaksi', $s['total_transaksi'] . ' Nota'],
      ['Rata-rata Penjualan / Nota', 'Rp ' . number_format($s['rata_rata_per_trx'], 0, ',', '.')],
      [],
      ['DISTRIBUSI METODE PEMBAYARAN', 'JUMLAH TRANSAKSI'],
      ['Tunai / Cash', $m['cash'] . ' Nota'],
      ['QRIS Digital', $m['qris'] . ' Nota'],
    ];
  }

  /**
   * Mendesain tampilan Worksheet Excel
   */
  public function styles(Worksheet $sheet): array
  {
    $headerStyle = [
      'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF059669'],
      ],
    ];

    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => 'FFAAAAAA'],
        ],
      ],
    ];

    $sheet->getStyle('A5:B5')->applyFromArray($headerStyle); // Header Ringkasan
    $sheet->getStyle('A13:B13')->applyFromArray($headerStyle); // Header Metode

    $sheet->getStyle('A5:B11')->applyFromArray($borderStyle); // Kotak Ringkasan
    $sheet->getStyle('A13:B15')->applyFromArray($borderStyle); // Kotak Metode

    return [
      1 => [
        'font' => [
          'bold' => true,
          'size' => 16,
          'color' => ['argb' => 'FF059669']
        ]
      ],
      2 => ['font' => ['bold' => true]],
      3 => ['font' => ['bold' => true]],
    ];
  }
}