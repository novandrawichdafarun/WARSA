<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;


class StokTable extends Component
{
  use WithPagination;

  public string $filterProduk = '';
  public string $filterTipe = '';
  public string $filterDariTgl = '';
  public string $filterSampaiTgl = '';

  public function updatingFilterProduk(): void
  {
    $this->resetPage();
  }
  public function updatingFilterTipe(): void
  {
    $this->resetPage();
  }
  public function updatingFilterDariTgl(): void
  {
    $this->resetPage();
  }
  public function updatingFilterSampaiTgl(): void
  {
    $this->resetPage();
  }

  public function resetFilter(): void
  {
    $this->filterProduk = '';
    $this->filterTipe = '';
    $this->filterDariTgl = '';
    $this->filterSampaiTgl = '';
    $this->resetPage();
  }

  public function render(): View
  {
    $movements = StockMovement::query()
      ->with(['product', 'user'])
      ->when($this->filterProduk, function ($q) {
        $q->where('product_id', $this->filterProduk);
      })
      ->when($this->filterTipe, function ($q) {
        $q->where('type', $this->filterTipe);
      })
      ->when($this->filterDariTgl, function ($q) {
        $q->whereDate('created_at', '>=', $this->filterDariTgl);
      })
      ->when($this->filterSampaiTgl, function ($q) {
        $q->whereDate('created_at', '<=', $this->filterSampaiTgl);
      })
      ->latest()
      ->paginate(15);

    $produkList = Product::orderBy('nama_produk')->get();

    return view('livewire.stok-table', compact('movements', 'produkList'));
  }
}