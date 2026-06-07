<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warung;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index()
    {
        $warungs = Warung::all();
        return view('super-admin.users.index', compact('warungs'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:owner,kasir,super_admin',
            'warung_id' => 'nullable|exists:warung,id',
            'is_verified' => 'boolean',
        ]);

        if (!$request->has('is_verified')) {
            $validatedData['is_verified'] = false;
        }

        $this->userService->updateUser($user, $validatedData);

        return redirect()->back()->with('success', 'Data User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return redirect()->back()->with('success', 'Data User berhasil dihapus!');
    }
}
