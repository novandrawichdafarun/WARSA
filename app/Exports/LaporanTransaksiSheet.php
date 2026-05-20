<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanTransaksiSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
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
}