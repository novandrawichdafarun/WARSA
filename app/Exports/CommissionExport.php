<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CommissionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
  protected $transactions;

  public function __construct($transactions)
  {
    $this->transactions = $transactions;
  }


  public function collection()
  {
    return $this->transactions;
  }

  public function headings(): array
  {
    return [
      'Waktu Selesai',
      'Toko (Mitra)',
      'Dicatat Oleh (Kasir)',
      'Omset Kotor Toko',
      'Komisi Masuk Sistem',
    ];
  }

  public function map($transaction): array
  {
    return [
      $transaction->paid_at->format('d/m/Y H:i'),
      $transaction->warung ? $transaction->warung->nama_warung : 'Toko Tidak Diketahui',
      $transaction->kasir ? $transaction->kasir->name : '-',
      'Rp ' . number_format($transaction->total_gross, 0, ',', '.'),
      'Rp ' . number_format($transaction->commission_amount, 0, ',', '.'),
    ];
  }

  public function styles(Worksheet $sheet)
  {
    return [
      1 => [
        'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
        'fill' => [
          'fillType' => Fill::FILL_SOLID,
          'startColor' => ['argb' => 'FF059669']
        ]
      ],
      'D:E' => [
        'alignment' => [
          'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
      ],
    ];
  }

  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $totalOmset = $this->transactions->sum('total_gross');
        $totalKomisi = $this->transactions->sum('commission_amount');

        $lastRow = $event->sheet->getHighestRow() + 1;

        $event->sheet->mergeCells("A{$lastRow}:C{$lastRow}");
        $event->sheet->setCellValue("A{$lastRow}", 'TOTAL KESELURUHAN');

        $event->sheet->setCellValue("D{$lastRow}", 'Rp ' . number_format($totalOmset, 0, ',', '.'));
        $event->sheet->setCellValue("E{$lastRow}", 'Rp ' . number_format($totalKomisi, 0, ',', '.'));

        $event->sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
          'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF']
          ],
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF059669']
          ],
          'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER
          ]
        ]);
      }
    ];
  }
}