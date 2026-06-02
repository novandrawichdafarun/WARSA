<?php

namespace App\Services;

use App\Models\User;
use App\Models\Warung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;

class WarungService
{
  /**
   * Memperbarui data Warung.
   * Tidak ada fungsi Create.
   */
  public function updateWarung(Warung $warung, array $data): Warung
  {
    try {
      DB::beginTransaction();

      $warung->update([
        'nama_warung' => $data['nama_warung'],
        'alamat' => $data['alamat'] ?? $warung->alamat,
        'no_telp' => $data['no_telp'] ?? $warung->no_telp,
      ]);

      if (!empty($data['tambah_user_id']) && !empty($data['tambah_user_role'])) {
        $userToAssign = User::find($data['tambah_user_id']);

        if ($userToAssign) {
          if ($data['tambah_user_role'] === 'owner') {
            $existingOwner = User::where('warung_id', $warung->id)
              ->where('role', 'owner')
              ->exists();

            if ($existingOwner) {
              throw ValidationException::withMessages([
                'tambah_user_role' => 'Gagal: Warung ini sudah memiliki Owner. Tidak bisa menambahkan Owner lagi.'
              ]);
            }
          }

          $userToAssign->update([
            'warung_id' => $warung->id,
            'role' => $data['tambah_user_role']
          ]);
        }
      }

      DB::commit();
      return $warung;

    } catch (Exception $e) {
      DB::rollBack();
      if (!$e instanceof ValidationException) {
        Log::error('Gagal memperbarui Warung: ' . $e->getMessage());
      }
      throw $e;
    }
  }

  /**
   * Menghapus Warung.
   */
  public function deleteWarung(Warung $warung): bool
  {
    try {
      DB::beginTransaction();

      $warung->users()->update(['warung_id' => null]);

      $warung->delete();

      DB::commit();
      return true;

    } catch (Exception $e) {
      DB::rollBack();
      Log::error('Gagal menghapus Warung: ' . $e->getMessage());
      throw $e;
    }
  }
}