<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Warung;
use App\Services\WarungService;
use Illuminate\Http\Request;

class WarungController extends Controller
{
    public function __construct(protected WarungService $warungService)
    {
    }

    public function index()
    {
        return view('super-admin.warungs.index');
    }

    public function update(Request $request, Warung $warung)
    {
        $validatedData = $request->validate([
            'nama_warung' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'tambah_user_id' => 'nullable|exists:users,id',
            'tambah_user_role' => 'nullable|in:owner,kasir',
        ]);

        $this->warungService->updateWarung($warung, $validatedData);

        return redirect()->back()->with('success', 'Data Warung berhasil diperbarui!');
    }

    public function destroy(Warung $warung)
    {
        $this->warungService->deleteWarung($warung);

        return redirect()->back()->with('success', 'Data Warung berhasil dihapus!');
    }
}
