<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanTransaksiSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting
{
  public function __construct(protected Carbon $dari, protected Carbon $sampai)
  {
  }

  public function title(): string
  {
    return 'Detail Transaksi';
  }

  public function query()
  {
    return Transaction::paid()
      ->with(['kasir', 'items'])
      ->whereBetween('paid_at', [
        $this->dari->startOfDay(),
        $this->sampai->copy()->endOfDay(),
      ])
      ->orderBy('paid_at');
  }

  public function headings(): array
  {
    return [
      'No. Transaksi',
      'Tanggal',
      'Waktu',
      'Kasir',
      'Jumlah Item',
      'Total Omset',
      'Komisi',
      'Total Net',
      'Metode Bayar',
    ];
  }

  public function map($transaksi): array
  {
    return [
      '#' . str_pad($transaksi->id, 6, '0', STR_PAD_LEFT),
      $transaksi->paid_at->format('d/m/Y'),
      $transaksi->paid_at->format('H:i'),
      $transaksi->kasir->name,
      $transaksi->items->count(),
      $transaksi->total_gross,
      $transaksi->commission_amount,
      $transaksi->total_net,
      strtoupper($transaksi->payment_method),
    ];
  }

  /**
   * Menerapkan format angka pada kolom spesifik di Excel
   */
  public function columnFormats(): array
  {
    // Kolom uang. Kita beri format pemisah ribuan.
    return [
      'F' => '"Rp "#,##0',
      'G' => '"Rp "#,##0',
      'H' => '"Rp "#,##0',
    ];
  }

  /**
   * Mengatur gaya visual (style) untuk lembar Excel
   */
  public function styles(Worksheet $sheet)
  {
    $highestRow = $sheet->getHighestRow();

    $sheet->getStyle('A1:I1')->applyFromArray([
      'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'], // Teks Putih
      ],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF059669'], // Background Emerald-600 WARSA
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ],
    ]);

    $sheet->getStyle('A1:I' . $highestRow)->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => 'FFAAAAAA'], // Warna garis abu-abu lembut
        ],
      ],
    ]);

    $sheet->getStyle('A2:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F2:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H2:H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    return [];
  }
}