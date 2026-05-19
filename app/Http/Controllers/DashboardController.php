<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $warung = Auth::user()->warung;

        $data = [
            'warung' => $warung,
            'total_produk' => Product::count(),
            'produk_low_stock' => Product::lowStock()->count(),
            'total_transaksi_hari_ini' => 0,
            'omset_hari_ini' => 0,
            'produk_low_stock_list' => Product::lowStock()->active()->with('category')->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}
