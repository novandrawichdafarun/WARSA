<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class ProdukTable extends Component
{
  use WithPagination;
  public string $search = '';
  public string $filterKategori = '';
  public string $filterStatus = '';

  public function render(): View
  {
    $produk = Product::query()
      ->with('category')
      ->when($this->search, function ($query) {
        $query->where('nama_produk', 'like', '%' . $this->search . '%');
      })
      ->when($this->filterKategori, function ($query) {
        $query->where('category_id', $this->filterKategori);
      })
      ->when($this->filterStatus !== '', function ($query) {
        $query->where('is_active', $this->filterStatus === 'aktif');
      })
      ->orderBy('nama_produk')
      ->paginate(10);

    $kategori = Category::orderBy('nama_kategori')->get();

    return view('livewire.produk-table', compact('produk', 'kategori'));
  }

  public function resetFilters(): void
  {
    $this->search = '';
    $this->filterKategori = '';
    $this->filterStatus = '';
  }
}
