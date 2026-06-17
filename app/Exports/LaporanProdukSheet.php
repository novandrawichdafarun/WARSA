<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanProdukSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithColumnFormatting
{
  public function __construct(protected array $laporan)
  {
  }

  public function title(): string
  {
    return 'Produk Terlaris';
  }

  public function collection()
  {
    return $this->laporan['produk_terlaris']->map(fn($item, $i) => [
      'No' => $i + 1,
      'Produk' => $item->nama_snapshot,
      'Total Terjual' => $item->total_qty,
      'Total Omset' => $item->total_omset,
    ]);
  }

  public function headings(): array
  {
    return ['No', 'Nama Produk', 'Total Terjual (unit)', 'Total Omset'];
  }

  /**
   * Mengatur format angka pada kolom
   */
  public function columnFormats(): array
  {
    return [
      'D' => '"Rp "#,##0',
    ];
  }

  /**
   * Mendesain tampilan Worksheet Excel
   */
  public function styles(Worksheet $sheet)
  {
    $highestRow = $sheet->getHighestRow();

    // 1. Gaya untuk baris Header (A1 sampai D1)
    $sheet->getStyle('A1:D1')->applyFromArray([
      'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'], // Teks warna putih
      ],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF059669'], // Background hijau Emerald WARSA
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
      ],
    ]);

    $sheet->getStyle('A1:D' . $highestRow)->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => 'FFAAAAAA'],
        ],
      ],
    ]);

    $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Kolom No
    $sheet->getStyle('C2:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Kolom Terjual
    $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Kolom Terjual

    return [];
  }
}