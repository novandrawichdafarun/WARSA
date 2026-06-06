<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;
  }

  public function createQris(Transaction $transaction): array
  {
    $orderId = 'WARSA-' . $transaction->id . '-' . time();

    $params = [
      'transaction_details' => [
        'order_id' => $orderId,
        'gross_amount' => $transaction->total_gross,
      ],
      'enabled_payments' => ['qris'], // hanya QRIS
      'expiry' => [
        'unit' => 'minutes',
        'duration' => 15, // QRIS expired dalam 15 menit
      ],
      'custom_field1' => $transaction->warung_id,
    ];

    try {
      $snapToken = Snap::getSnapToken($params);
    } catch (\Exception $e) {
      Log::error('Midtrans QRIS error: ' . $e->getMessage());
      throw new \Exception('Gagal membuat QRIS. Pastikan konfigurasi Midtrans benar.');
    }

    return [
      'order_id' => $orderId,
      'snap_token' => $snapToken,
    ];
  }

  public function verifySignature(
    string $orderId,
    string $statusCode,
    string $grossAmount,
    string $receivedSignature
  ): bool {
    $expectedSignature = hash(
      'sha512',
      $orderId . $statusCode . $grossAmount . config('services.midtrans.server_key')
    );

    return hash_equals($expectedSignature, $receivedSignature);
  }
}