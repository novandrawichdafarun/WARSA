<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\CommissionExport;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Warung;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

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
      'warung_id' => ['nullable', 'exists:warung,id'],
    ]);

    [$dari, $sampai] = $this->laporanService->parsePeriode(
      dari: $request->dari,
      sampai: $request->sampai,
      preset: $request->input('preset', 'bulan_ini'),
    );

    $query = Transaction::paid()
      ->whereBetween('paid_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()]);

    if ($request->filled('warung_id')) {
      $query->where('warung_id', $request->warung_id);
    }

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

    $persentaseKomisi = $totalOmset > 0 ? ($totalKomisi / $totalOmset) * 100 : 0;

    $warungs = Warung::orderBy('nama_warung', 'asc')->get();

    return view('super-admin.commission.index', compact(
      'dari',
      'sampai',
      'totalOmset',
      'totalKomisi',
      'totalTransaksi',
      'rataRata',
      'transaksiHarian',
      'transaksi',
      'persentaseKomisi',
      'warungs'
    ));
  }

  public function exportPdf(Request $request)
  {
    $request->validate([
      'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
      'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
      'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
      'warung_id' => ['nullable', 'exists:warung,id'],
    ]);

    [$dari, $sampai] = $this->laporanService->parsePeriode(
      dari: $request->dari,
      sampai: $request->sampai,
      preset: $request->input('preset', 'bulan_ini'),
    );

    $query = Transaction::paid()
      ->whereBetween('paid_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()]);

    $namaWarung = 'Semua Toko';
    if ($request->filled('warung_id')) {
      $query->where('warung_id', $request->warung_id);
      $warung = Warung::find($request->warung_id);
      $namaWarung = $warung ? $warung->nama_warung : 'Semua Toko';
    }

    $transactions = $query->with(['warung', 'kasir'])->latest('paid_at')->get();

    $totalOmset = $transactions->sum('total_gross');
    $totalKomisi = $transactions->sum('commission_amount');

    $pdf = Pdf::loadView('super-admin.commission.pdf', compact(
      'transactions',
      'dari',
      'sampai',
      'totalOmset',
      'totalKomisi',
      'namaWarung'
    ))->setPaper('a4', 'portrait')->setOptions([
          'defaultFont' => 'sans-serif',
          'isHtml5ParserEnabled' => true,
          'isRemoteEnabled' => false,
        ]);

    $namaFile = 'Laporan_Komisi_' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.pdf';

    return $pdf->download($namaFile);
  }

  public function exportExcel(Request $request)
  {
    $request->validate([
      'dari' => ['nullable', 'date', 'before_or_equal:sampai'],
      'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
      'preset' => ['nullable', 'in:hari_ini,minggu_ini,bulan_ini,bulan_lalu,tahun_ini'],
      'warung_id' => ['nullable', 'exists:warung,id'],
    ]);

    [$dari, $sampai] = $this->laporanService->parsePeriode(
      dari: $request->dari,
      sampai: $request->sampai,
      preset: $request->input('preset', 'bulan_ini'),
    );

    $query = Transaction::paid()
      ->whereBetween('paid_at', [$dari->startOfDay(), $sampai->copy()->endOfDay()]);

    if ($request->filled('warung_id')) {
      $query->where('warung_id', $request->warung_id);
    }

    $transactions = $query->with(['warung', 'kasir'])->latest('paid_at')->get();

    $namaFile = 'Laporan_Komisi_' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.xlsx';

    return Excel::download(new CommissionExport($transactions), $namaFile);
  }
}