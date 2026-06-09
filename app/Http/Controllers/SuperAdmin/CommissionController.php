<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
  public function __construct(protected LaporanService $laporanService)
  {
  }

  public function index(Request $request): View
  {
    $request->validate([
      'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
      'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
      'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
    ]);

    [$dari, $sampai] = $this->laporanService->parsePeriode(
      dari: $request->dari,
      sampai: $request->sampai,
      preset: $request->input('preset', 'bulan_ini'),
    );

    $query = Transaction::paid()
      ->whereBetween('paid_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()]);

    $totalOmset = (clone $query)->sum('total_gross');
    $totalKomisi = (clone $query)->sum('commission_amount');
    $totalTransaksi = (clone $query)->count();
    $rataRata = $totalTransaksi > 0 ? $totalKomisi / $totalTransaksi : 0;

    $transaksiHarian = (clone $query)
      ->selectRaw('DATE(paid_at) as tanggal, SUM(commission_amount) as komisi')
      ->groupBy('tanggal')
      ->orderBy('tanggal')
      ->get();

    $transaksi = (clone $query)
      ->with(['warung', 'kasir'])
      ->latest('paid_at')
      ->paginate(20)
      ->withQueryString();

    return view('super-admin.commission.index', compact(
      'dari',
      'sampai',
      'totalOmset',
      'totalKomisi',
      'totalTransaksi',
      'rataRata',
      'transaksiHarian',
      'transaksi'
    ));
  }
}