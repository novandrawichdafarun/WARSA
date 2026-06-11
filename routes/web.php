<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\WarungSetupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SuperAdmin\CommissionController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\WarungController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
// OWNER WARUNG & SUPER ADMIN
// ============================================================
Route::middleware(['auth', 'owner_or_superadmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])
        ->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])
        ->name('pengaturan.update');
});

// ============================================================
// OWNER WARUNG 
// ============================================================
Route::middleware(['auth', 'warung.setup', 'owner'])->group(function () {
    Route::resource('karyawan', KaryawanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('produk', ProdukController::class);
    Route::post('produk/{produk}/stok', [ProdukController::class, 'tambahStok'])
        ->name('produk.stok.tambah');
    Route::resource('kategori', CategoryController::class)
        ->only(['index', 'store', 'destroy']);

    Route::get('/stok', [StockController::class, 'index'])
        ->name('stok.index');
    Route::get('/stok/tambah', [StockController::class, 'create'])
        ->name('stok.create');
    Route::post('/stok/tambah', [StockController::class, 'store'])
        ->name('stok.store');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])
        ->name('laporan.export.excel');
    Route::get('/laporan/komisi', [LaporanController::class, 'komisi'])
        ->name('laporan.komisi');
});

// ============================================================
// KASIR WARUNG
// ============================================================
Route::middleware(['auth', 'warung.setup', 'kasir'])->group(function () {
    Route::get('/pos', [TransaksiController::class, 'pos'])
        ->name('pos.index');

    Route::post('/transaksi', [TransaksiController::class, 'store'])
        ->name('transaksi.store');
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat'])
        ->name('transaksi.riwayat');
    Route::get('/transaksi/{transaksi}/status', [TransaksiController::class, 'checkStatus'])
        ->name('transaksi.status');
    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])
        ->name('transaksi.struk');
    Route::patch('/transaksi/{transaksi}/batal', [TransaksiController::class, 'batal'])
        ->name('transaksi.batal');
});

// ============================================================
// SUPER ADMIN
// ============================================================
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');
    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');

    Route::get('/warungs', [WarungController::class, 'index'])
        ->name('warungs.index');
    Route::put('/warungs/{warung}', [WarungController::class, 'update'])
        ->name('warungs.update');
    Route::delete('/warungs/{warung}', [WarungController::class, 'destroy'])
        ->name('warungs.destroy');

    Route::get('/commission', [CommissionController::class, 'index'])
        ->name('commission.index');
});

require __DIR__ . '/auth.php';