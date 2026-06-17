<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function pos(): View
    {
        return view('pos.index');
    }

    public function store(Request $request, TransactionService $transactionService): JsonResponse|RedirectResponse
    {
        abort(404);
    }

    public function checkStatus(Transaction $transaksi): JsonResponse
    {
        abort_if($transaksi->warung_id !== Auth::user()->warung_id, 403);

        return response()->json([
            'status' => $transaksi->payment_status,
            'paid_at' => $transaksi->paid_at,
        ]);
    }

    public function updateStatus(Transaction $transaksi, TransactionService $transactionService): RedirectResponse
    {
        abort_if(!Auth::user()->canAccessPOS(), 403);
        abort_if($transaksi->warung_id !== Auth::user()->warung_id, 403);

        if ($transaksi->payment_status === 'pending' && $transaksi->payment_method === 'qris') {
            $transactionService->settle($transaksi);

            return back()->with('success', 'Pembayaran QRIS berhasil dikonfirmasi dan dilunasi.');
        }

        return back()->with('error', 'Status transaksi tidak dapat diupdate.');
    }

    public function struk(Transaction $transaksi): View
    {
        abort_if($transaksi->warung_id !== Auth::user()->warung_id, 403);
        abort_if(!$transaksi->isPaid(), 404);

        $transaksi->load(['items', 'kasir', 'warung']);

        return view('transaksi.struk', compact('transaksi'));
    }

    public function riwayat(): View
    {
        $transaksi = Transaction::with(['items', 'kasir'])
            ->latest('created_at')
            ->paginate(20);

        $totalOmsetHariIni = Transaction::paid()->today()->sum('total_gross');
        $totalTrxHariIni = Transaction::paid()->today()->count();

        return view('transaksi.riwayat', compact('transaksi', 'totalOmsetHariIni', 'totalTrxHariIni'));
    }

    public function batal(Transaction $transaksi, TransactionService $transactionService): RedirectResponse
    {
        abort_if($transaksi->warung_id !== Auth::user()->warung_id, 403);

        $transactionService->cancel($transaksi);

        return back()->with('success', 'Transaksi berhasil dibatalkan.');
    }
}
