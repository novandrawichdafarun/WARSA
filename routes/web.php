<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\WarungSetupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/setup-warung', [WarungSetupController::class, 'create'])
        ->name('warung.setup');
    Route::post('/setup-warung', [WarungSetupController::class, 'store'])
        ->name('warung.setup.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// OWNER WARUNG 
// ============================================================
Route::middleware(['auth', 'warung.setup', 'owner'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('karyawan', KaryawanController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    Route::get('/pengaturan', [PengaturanController::class, 'index'])
        ->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])
        ->name('pengaturan.update');

    Route::resource('produk', ProdukController::class);
    Route::post('produk/{produk}/stok', [ProdukController::class, 'tambahStok'])
        ->name('produk.stok.tambah');
    Route::resource('kategori', CategoryController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('stok', StockController::class);
    Route::get('/stok', [StockController::class, 'index'])
        ->name('stok.index');
    Route::get('/stok/tambah', [StockController::class, 'create'])
        ->name('stok.create');
    Route::post('/stok/tambah', [StockController::class, 'store'])
        ->name('stok.store');

    // Route::get('/laporan', [LaporanController::class, 'index']);
});

// ============================================================
// KASIR WARUNG
// ============================================================
Route::middleware(['auth', 'warung.setup', 'kasir'])->group(function () {
    Route::get('/pos', [TransaksiController::class, 'pos'])
        ->name('pos.index');

    Route::post('/transaksi', [TransaksiController::class, 'store'])
        ->name('transaksi.store');
    Route::get('/transaksi/{transaksi}/status', [TransaksiController::class, 'checkStatus'])
        ->name('transaksi.status');
    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])
        ->name('transaksi.struk');
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat'])
        ->name('transaksi.riwayat');
    Route::patch('/transaksi/{transaksi}/batal', [TransaksiController::class, 'batal'])
        ->name('transaksi.batal');
});

// Route::middleware(['auth', 'warung.setup', 'kasir'])->group(function () {
//     Route::get('/pos', function () {
//         return view('pos.index');
//     })->name('pos.index');

//     Route::get('/transaksi/riwayat', function () {
//         return view('transaksi.riwayat');
//     })->name('transaksi.riwayat');
// });

Route::post('/webhook/midtrans', [WebhookController::class, 'handle'])
    ->name('webhook.midtrans');

require __DIR__ . '/auth.php';