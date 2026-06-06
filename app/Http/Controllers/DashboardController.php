<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(LaporanService $laporanService): View
    {
        $warung = Auth::user()->warung;
        // $warungId = Auth::user()->warung_id;

        $omsetHariIni = Transaction::paid()->today()->sum('total_gross');
        $totalTransaksiHariIni = Transaction::paid()->today()->count();
        $omsetBulanIni = Transaction::paid()->thisMonth()->sum('total_gross');

        // $chartHarian = Cache::remember(
        //     "dashboard_chart_{$warungId}",
        //     now()->addMinutes(5),
        //     fn() => Transaction::paid()
        //         ->thisMonth()
        //         ->selectRaw('DATE(paid_at) as tanggal, SUM(total_gross) as omset')
        //         ->groupBy('tanggal')
        //         ->orderBy('tanggal')
        //         ->get()
        // );

        $data = [
            'warung' => $warung,
            'total_produk' => Product::count(),
            'produk_low_stock' => Product::lowStock()->count(),
            'produk_low_stock_list' => Product::lowStock()->active()->with('category')->take(5)->get(),
            'omset_hari_ini' => $omsetHariIni,
            'total_transaksi_hari_ini' => $totalTransaksiHariIni,
            'omset_bulan_ini' => $omsetBulanIni,
            // 'chart_harian' => $chartHarian,
        ];

        return view('dashboard', $data);
    }
}
