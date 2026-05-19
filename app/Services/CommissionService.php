<?php

namespace App\Services;

use App\Models\CommissionLedger;
use App\Models\Transaction;

class CommissionService
{
  /**
   * Hitung dan catat komisi setiap kali transaksi berhasil paid.
   * Dipanggil oleh TransactionService setelah status = paid.
   *
   * Rate diambil dari transaksi itu sendiri (bukan hardcode di sini)
   * agar histori tetap akurat jika rate berubah di masa depan.
   */

  public function record(Transaction $transaction): CommissionLedger
  {
    if ($transaction->commissionLedger()->exists()) {
      return $transaction->commissionLedger;
    }

    $commissionAmount = (int) round(
      $transaction->total_gross * $transaction->commission_rate
    );

    return CommissionLedger::create([
      'warung_id' => $transaction->warung_id,
      'transaction_id' => $transaction->id,
      'gross_amount' => $transaction->total_gross,
      'commission_rate' => $transaction->commission_rate,
      'commission_amount' => $commissionAmount,
      'status' => 'settled',
      'settled_at' => now(),
    ]);
  }
}