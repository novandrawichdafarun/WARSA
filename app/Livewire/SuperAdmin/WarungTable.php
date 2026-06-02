<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Warung;
use Livewire\Component;
use Livewire\WithPagination;

class WarungTable extends Component
{
  use WithPagination;

  public $search = '';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function render()
  {
    $warungs = Warung::with('users')->where('nama_warung', 'like', '%' . $this->search . '%')
      ->paginate(10);

    $availableUsers = User::whereNull('warung_id')->where('role', '!=', 'super_admin')->get();

    return view('livewire.super-admin.warung-table', compact('warungs', 'availableUsers'));
  }
}
