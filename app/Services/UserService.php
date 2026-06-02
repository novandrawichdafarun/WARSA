<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserService
{
  /**
   * Memperbarui data User.
   * Tidak ada fungsi Create di sini sesuai batasan Super Admin.
   */
  public function updateUser(User $user, array $data): User
  {
    try {
      DB::beginTransaction();

      if (isset($data['role']) && $data['role'] === 'owner' && !empty($data['warung_id'])) {
        $existingOwner = User::where('warung_id', $data['warung_id'])
          ->where('role', 'owner')
          ->where('id', '!=', $user->id)
          ->exists();

        if ($existingOwner) {
          throw ValidationException::withMessages([
            'role' => 'Gagal: Warung yang dipilih sudah memiliki 1 Owner. Silakan pilih warung lain atau ubah role menjadi Kasir.'
          ]);
        }
      }

      $updateData = [
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'],
        'warung_id' => $data['warung_id'] ?? null,
      ];

      if (!empty($data['password'])) {
        $updateData['password'] = $data['password'];
      }

      if (isset($data['is_verified'])) {
        $updateData['email_verified_at'] = $data['is_verified'] ? now() : null;
      }

      $user->update($updateData);

      DB::commit();
      return $user;

    } catch (Exception $e) {
      DB::rollBack();
      if (!$e instanceof ValidationException) {
        Log::error('Gagal memperbarui User: ' . $e->getMessage());
      }
      throw $e;
    }
  }

  /**
   * Menghapus (Soft Delete) data User.
   */
  public function deleteUser(User $user): bool
  {
    try {
      return $user->delete();

    } catch (Exception $e) {
      Log::error('Gagal menghapus User: ' . $e->getMessage());
      throw $e;
    }
  }
}