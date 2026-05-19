<?php

namespace App\Http\Controllers;

use App\Http\Requests\TambahStokRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $totalMasukHariIni = StockMovement::whereDate('created_at', today())
            ->where('type', 'in')
            ->sum('quantity');

        $totalKeluarHariIni = StockMovement::whereDate('created_at', today())
            ->where('type', 'out')
            ->sum('quantity');

        $produkLowStock = Product::lowStock()
            ->active()
            ->with('category')
            ->get();

        return view('stok.index', compact(
            'totalMasukHariIni',
            'totalKeluarHariIni',
            'produkLowStock'
        ));
    }

    public function create(): View
    {
        $produk = Product::active()
            ->orderBy('nama_produk')
            ->get();

        return view('stok.create', compact('produk'));
    }

    public function store(TambahStokRequest $request, StockService $stockService): RedirectResponse
    {
        $produk = Product::findOrFail($request->product_id);

        abort_if($produk->warung_id !== Auth::user()->warung_id, 403);

        $stockService->tambahStok(
            product: $produk,
            jumlah: $request->jumlah,
            keterangan: $request->keterangan
        );

        return redirect()->route('stok.index')
            ->with('success', "Stok {$produk->nama_produk} berhasil ditambah {$request->jumlah} unit.");
    }
}
