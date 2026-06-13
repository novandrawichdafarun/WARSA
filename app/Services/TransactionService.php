<?php

namespace App\Services;

use App\Events\PesananBaruDibuat;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TransactionService
{
  public function __construct(
    protected StockService $stockService,
    protected CommissionService $commissionService,
  ) {
  }

  public function create(array $items, string $paymentMethod): Transaction
  {
    return DB::transaction(function () use ($items, $paymentMethod) {
      $totalGross = 0;
      $resolvedItems = [];

      foreach ($items as $item) {
        $produk = Product::lockForUpdate()->findOrFail($item['product_id']);

        if (!$produk->hasEnoughStock($item['qty'])) {
          throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi. Tersisa: {$produk->stok}");
        }

        $subtotal = $produk->harga_jual * $item['qty'];
        $totalGross += $subtotal;
        $resolvedItems[] = [
          'produk' => $produk,
          'qty' => $item['qty'],
          'subtotal' => $subtotal,
        ];
      }

      $commissionRate = env('WARSA_KOMISI',  0.005);
      $commissionAmount = (int) round($totalGross * $commissionRate);
      $totalNet = $totalGross - $commissionAmount;

      $transaction = Transaction::create([
        'warung_id' => Auth::user()->warung_id,
        'user_id' => Auth::id(),
        'total_gross' => $totalGross,
        'commission_rate' => $commissionRate,
        'commission_amount' => $commissionAmount,
        'total_net' => $totalNet,
        'payment_method' => $paymentMethod,
        'payment_status' => 'pending',
      ]);

      foreach ($resolvedItems as $resolved) {
        TransactionItem::create([
          'transaction_id' => $transaction->id,
          'product_id' => $resolved['produk']->id,
          'nama_snapshot' => $resolved['produk']->nama_produk,
          'harga_snapshot' => $resolved['produk']->harga_jual,
          'quantity' => $resolved['qty'],
          'subtotal' => $resolved['subtotal'],
        ]);
      }

      if ($paymentMethod === 'cash') {
        $this->settle($transaction, $resolvedItems);
      }

      if ($paymentMethod === 'qris') {
        $transaction->update([
          'payment_status' => 'pending',
        ]);

        event(new PesananBaruDibuat($transaction));
      }

      return $transaction->fresh(['items', 'commissionLedger']);
    });
  }

  public function settle(Transaction $transaction, array $resolvedItems = []): void
  {
    if (empty($resolvedItems)) {
      $resolvedItems = $transaction->items->map(fn($item) => [
        'produk' => $item->product,
        'qty' => $item->quantity,
      ])->toArray();
    }

    foreach ($resolvedItems as $resolved) {
      if (isset($resolved['produk']) && $resolved['produk']) {
        $this->stockService->kurangiStok(
          product: $resolved['produk'],
          jumlah: $resolved['qty'],
          transactionId: $transaction->id,
          keterangan: 'Penjualan #' . $transaction->id,
        );
      }
    }

    $transaction->update([
      'payment_status' => 'paid',
      'paid_at' => now(),
    ]);

    Cache::forget("dashboard_laporan_{$transaction->warung_id}");

    $this->commissionService->record($transaction);
  }

  public function cancel(Transaction $transaction): void
  {
    abort_if(!$transaction->isPending(), 422, 'Hanya transaksi pending yang bisa dibatalkan.');

    $transaction->update([
      'payment_status' => 'cancelled',
      'cancelled_at' => now(),
    ]);
  }
}