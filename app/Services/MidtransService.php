<?php

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('services.midtrans.client_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;
  }

  public function createQris(Transaction $transaction): array
  {
    $orderId = 'SIWARUNG-' . $transaction->id . '-' . time();

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

    $snapToken = Snap::getSnapToken($params);

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