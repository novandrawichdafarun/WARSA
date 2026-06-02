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
        $warungId = Auth::user()->warung_id;
        $laporan = Cache::remember(
            "dashboard_laporan_{$warungId}",
            now()->addMinutes(5),
            fn() => $laporanService->getSummary(now()->startOfMonth(), now()->endOfMonth())
        );

        $data = [
            'warung' => $warung,
            'total_produk' => Product::count(),
            'produk_low_stock' => Product::lowStock()->count(),
            'produk_low_stock_list' => Product::lowStock()->active()->with('category')->take(5)->get(),
            'omset_hari_ini' => Transaction::paid()->today()->sum('total_gross'),
            'total_transaksi_hari_ini' => Transaction::paid()->today()->count(),
            'omset_bulan_ini' => $laporan['summary']['total_omset'],
            'chart_harian' => $laporan['transaksi_harian'],
        ];

        return view('dashboard', $data);
    }
}
