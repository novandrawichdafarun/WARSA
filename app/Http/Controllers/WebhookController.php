<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\MidtransService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request, TransactionService $transactionService, MidtransService $midtransService)
    {
        $payload = $request->all();

        $required = ['order_id', 'status_code', 'gross_amount', 'signature_key', 'transaction_status'];
        foreach ($required as $field) {
            if (empty($payload[$field])) {
                return response()->json(['message' => 'Invalid payload'], 400);
            }
        }

        $isValid = $midtransService->verifySignature(
            orderId: $payload['order_id'],
            statusCode: $payload['status_code'],
            grossAmount: $payload['gross_amount'],
            receivedSignature: $payload['signature_key'],
        );

        if (!$isValid) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = Transaction::where('midtrans_order_id', $payload['order_id'])->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $status = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($status === 'settlement' || ($status === 'capture' && $fraudStatus === 'accept')) {
            if ($transaksi->isPending()) {
                $transactionService->settle($transaksi);
            }
        }

        if (in_array($status, ['cancel', 'deny', 'expire'])) {
            $transactionService->cancel($transaksi);
        }

        return response()->json(['message' => 'OK']);
    }
}
