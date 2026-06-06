<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanService
{
  public function getSummary(Carbon $dari, Carbon $sampai): array
  {
    $baseQuery = Transaction::paid()
      ->whereBetween('paid_at', [
        $dari->copy()->startOfDay(),
        $sampai->copy()->endOfDay(),
      ]);

    $totalOmset = (clone $baseQuery)->sum('total_gross');
    $totalNet = (clone $baseQuery)->sum('total_net');
    $totalKomisi = (clone $baseQuery)->sum('commission_amount');
    $totalTransaksi = (clone $baseQuery)->count();
    $rataRataPerTrx = $totalTransaksi > 0 ? (int) round($totalOmset / $totalTransaksi) : 0;

    $labaKotor = (clone $baseQuery)
      ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
      ->leftJoin('products', 'transaction_items.product_id', '=', 'products.id')
      ->sum(DB::raw('(transaction_items.harga_snapshot - COALESCE(products.harga_beli, 0)) * transaction_items.quantity'));

    $transaksiHarian = (clone $baseQuery)
      ->selectRaw('DATE(paid_at) as tanggal, SUM(total_gross) as omset, COUNT(*) as jumlah')
      ->groupBy('tanggal')
      ->orderBy('tanggal')
      ->get();

    $produkTerlaris = TransactionItem::query()
      ->whereHas('transaction', function ($q) use ($dari, $sampai) {
        $q->paid()->whereBetween('paid_at', [
          $dari->startOfDay(),
          $sampai->copy()->endOfDay(),
        ]);
      })
      ->selectRaw('nama_snapshot, SUM(quantity) as total_qty, SUM(subtotal) as total_omset')
      ->groupBy('nama_snapshot')
      ->orderByDesc('total_qty')
      ->take(10)
      ->get();

    $metodeBayar = [
      'cash' => (clone $baseQuery)->where('payment_method', 'cash')->count(),
      'qris' => (clone $baseQuery)->where('payment_method', 'qris')->count(),
    ];

    return [
      'periode' => [
        'dari' => $dari->format('d M Y'),
        'sampai' => $sampai->format('d M Y'),
      ],
      'summary' => [
        'total_omset' => $totalOmset,
        'total_net' => $totalNet,
        'total_komisi' => $totalKomisi,
        'total_transaksi' => $totalTransaksi,
        'rata_rata_per_trx' => $rataRataPerTrx,
        'laba_kotor' => $labaKotor,
      ],
      'transaksi_harian' => $transaksiHarian,
      'produk_terlaris' => $produkTerlaris,
      'metode_bayar' => $metodeBayar,
    ];
  }

  public function parsePeriode(?string $dari, ?string $sampai, string $preset = 'bulan_ini'): array
  {
    if ($dari && $sampai) {
      return [
        Carbon::parse($dari)->startOfDay(),
        Carbon::parse($sampai)->endOfDay(),
      ];
    }

    return match ($preset) {
      'hari_ini' => [now()->startOfDay(), now()->endOfDay()],
      'minggu_ini' => [now()->startOfWeek(), now()->endOfWeek()],
      'bulan_lalu' => [
        now()->subMonth()->startOfMonth(),
        now()->subMonth()->endOfMonth(),
      ],
      'tahun_ini' => [now()->startOfYear(), now()->endOfYear()],
      default => [now()->startOfMonth(), now()->endOfMonth()], // bulan_ini
    };
  }
}