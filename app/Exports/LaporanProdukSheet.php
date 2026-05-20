<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanProdukSheet implements FromCollection, WithHeadings, WithTitle
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
    return ['No', 'Nama Produk', 'Total Terjual (unit)', 'Total Omset (Rp)'];
  }
}