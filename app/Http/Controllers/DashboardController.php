<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CommissionLedger;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $commisi_harian = Cache::remember(
                "dashboard_chart_super_admin",
                now()->addMinutes(5),
                fn() => CommissionLedger::selectRaw('DATE(created_at) as tanggal, SUM(commission_amount) as omset')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->groupBy('tanggal')
                    ->orderBy('tanggal')
                    ->get()
                    ->toArray()
            );
            $data = [
                'totalWarung' => Warung::count(),
                'totalUser' => User::where('role', '!=', 'super_admin')->count(),
                'total_transaksi_hari_ini' => Transaction::paid()->today()->count(),
                'omset_hari_ini' => CommissionLedger::whereDate('created_at', today())->sum('commission_amount'),
                'chart_harian' => $commisi_harian,
            ];

            return view('dashboard', $data);
        }

        $warung = Auth::user()->warung;

        $omsetHariIni = Transaction::paid()->today()->sum('total_gross');
        $totalTransaksiHariIni = Transaction::paid()->today()->count();
        $omsetBulanIni = Transaction::paid()->thisMonth()->sum('total_gross');

        $chartHarian = Cache::remember(
            "dashboard_chart_{$warung->id}",
            now()->addMinutes(5),
            fn() => Transaction::paid()
                ->thisMonth()
                ->selectRaw('DATE(paid_at) as tanggal, SUM(total_gross) as omset')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get()
                ->toArray()
        );

        $data = [
            'warung' => $warung,
            'total_produk' => Product::count(),
            'produk_low_stock' => Product::lowStock()->count(),
            'produk_low_stock_list' => Product::lowStock()->active()->with('category')->take(5)->get(),
            'omset_hari_ini' => $omsetHariIni,
            'total_transaksi_hari_ini' => $totalTransaksiHariIni,
            'omset_bulan_ini' => $omsetBulanIni,
            'chart_harian' => $chartHarian,
        ];

        return view('dashboard', $data);
    }
}
