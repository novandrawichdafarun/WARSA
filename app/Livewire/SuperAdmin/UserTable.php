<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
  use WithPagination;

  public $search = '';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function render()
  {
    $users = User::with('warung')
      ->where('name', 'like', '%' . $this->search . '%')
      ->orWhere('email', 'like', '%' . $this->search . '%')
      ->paginate(10);

    return view('livewire.super-admin.user-table', compact('users'));
  }
}