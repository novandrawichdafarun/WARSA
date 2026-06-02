<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class PosKasir extends Component
{
  use WithPagination;

  public string $mode = 'klasik';
  public string $search = '';
  public string $filterKategori = '';

  public array $keranjang = [];

  public string $paymentMethod = 'cash';
  public bool $showCheckout = false;

  public ?int $transaksiId = null;
  public ?string $snapToken = null;
  public bool $qrisDisplayed = false;

  public ?string $errorMessage = null;

  public function getTotalAttribute(): int
  {
    return collect($this->keranjang)->sum('subtotal');
  }

  public function getTotalItemsAttribute(): int
  {
    return collect($this->keranjang)->sum('qty');
  }

  // =========================================================
  // KERANJANG METHODS
  // =========================================================
  public function tambahKeKeranjang(int $productId): void
  {
    $key = (string) $productId;

    $produk = Product::find($productId);
    if (!$produk || !$produk->is_active) {
      $this->errorMessage = 'Produk tidak tersedia.';
      return;
    }

    $qtyDiKeranjang = $this->keranjang[$key]['qty'] ?? 0;
    if (!$produk->hasEnoughStock($qtyDiKeranjang + 1)) {
      $this->errorMessage = "Stok {$produk->nama_produk} tidak mencukupi!";
      return;
    }

    if (isset($this->keranjang[$key])) {
      $this->keranjang[$key]['qty']++;
      $this->keranjang[$key]['subtotal'] =
        $this->keranjang[$key]['qty'] * $this->keranjang[$key]['harga'];
    } else {
      $this->keranjang[$key] = [
        'nama' => $produk->nama_produk,
        'harga' => $produk->harga_jual,
        'qty' => 1,
        'subtotal' => $produk->harga_jual,
        'foto' => $produk->foto,
      ];
    }

    $this->errorMessage = null;
  }

  public function kurangiDariKeranjang(int $productId): void
  {
    $key = (string) $productId;

    if (!isset($this->keranjang[$key])) {
      $this->errorMessage = 'Produk tidak ada di keranjang.';
      return;
    }

    if ($this->keranjang[$key]['qty'] <= 1) {
      $this->hapusDariKeranjang($key);
      return;
    }

    $this->keranjang[$key]['qty']--;
    $this->keranjang[$key]['subtotal'] =
      $this->keranjang[$key]['qty'] * $this->keranjang[$key]['harga'];
  }

  public function hapusDariKeranjang(int $productId): void
  {
    $key = (string) $productId;

    if (isset($this->keranjang[$key])) {
      unset($this->keranjang[$key]);
    }
  }

  public function kosongkanKeranjang(): void
  {
    $this->keranjang = [];
    $this->showCheckout = false;
    $this->transaksiId = null;
    $this->snapToken = null;
    $this->qrisDisplayed = false;
    $this->errorMessage = null;
  }

  // =========================================================
  // CHECKOUT METHODS
  // =========================================================

  public function prosesCheckout(TransactionService $transactionService): void
  {
    if (empty($this->keranjang)) {
      $this->errorMessage = "Keranjang masih kosong!";
      return;
    }

    try {
      $items = collect($this->keranjang)
        ->map(fn($item, $key) => [
          'product_id' => (int) $key,
          'qty' => $item['qty'],
        ])
        ->values()
        ->toArray();

      $transaksi = $transactionService->create($items, $this->paymentMethod);
      $this->transaksiId = $transaksi->id;

      if ($this->paymentMethod == 'qris') {
        $this->snapToken = $transaksi->midtrans_snap_token;
        $this->qrisDisplayed = true;
      } else {
        $this->redirect(route('transaksi.struk', $transaksi->id));
      }
    } catch (\Exception $e) {
      $this->errorMessage = $e->getMessage();
    }
  }

  public function cekStatusQris(): void
  {
    if (!$this->transaksiId || !$this->qrisDisplayed)
      return;

    $transaksi = Transaction::find($this->transaksiId);

    if ($transaksi && $transaksi->isPaid()) {
      $this->redirect(route('transaksi.struk', $transaksi->id));
    }

    if ($transaksi && $transaksi->isCancelled()) {
      $this->errorMessage = "Pembayaran dibatalkan atau expired.";
      $this->qrisDisplayed = false;
      $this->snapToken = null;
    }
  }

  public function toggleMode(): void
  {
    $this->mode = $this->mode === 'klasik' ? 'katalog' : 'klasik';
    $this->resetPage();
  }

  public function render()
  {
    $produk = Product::query()
      ->active()
      ->with('category')
      ->when(
        $this->search,
        fn($q) =>
        $q->where('nama_produk', 'like', "%{$this->search}%")
      )
      ->when(
        $this->filterKategori,
        fn($q) =>
        $q->where('category_id', $this->filterKategori)
      )
      ->orderBy('nama_produk')
      ->paginate($this->mode === 'katalog' ? 12 : 20);

    $kategori = Category::orderBy('nama_kategori')->get();
    $total = $this->getTotalAttribute();
    $totalItems = $this->getTotalItemsAttribute();

    return view('livewire.pos-kasir', compact('produk', 'kategori', 'total', 'totalItems'));
  }

  public function handleLivewireException(Throwable $e): void
  {
    $this->errorMessage = 'Terjadi Kesalahan: ' . $e->getMessage() . '. Hubungi administrator jika masalah berlanjut.';
    Log::error('POS Error: ' . $e->getMessage(), [
      'user_id' => Auth::id(),
      'keranjang' => $this->keranjang,
      'trace' => $e->getTraceAsString(),
    ]);
  }
}