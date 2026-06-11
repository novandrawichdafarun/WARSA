<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function index(): View
    {
        $karyawan = User::where('warung_id', Auth::user()->warung_id)
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->paginate(10);

        return view('karyawan.index', compact('karyawan'));
    }

    public function create(): View
    {
        return view('karyawan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:kasir,pelanggan'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'warung_id' => Auth::user()->warung_id,
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Akun ' . ucfirst($request->role) . ' berhasil dibuat.');
    }

    public function edit(User $karyawan): View
    {
        return view('karyawan.edit', ['user' => $karyawan]);
    }

    public function update(Request $request, User $karyawan): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $karyawan->id],
            'accepted_role' => ['required', 'in:kasir,pelanggan'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->role = $request->accepted_role;

        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }

        $karyawan->save();

        return redirect()->route('karyawan.index')
            ->with('success', 'Akun ' . ucfirst($karyawan->role) . ' berhasil diperbarui.');
    }

    public function destroy(User $karyawan): RedirectResponse
    {
        abort_if($karyawan->warung_id !== Auth::user()->warung_id, 403);
        abort_if($karyawan->isOwner(), 403, 'Tidak bisa menghapus akun owner.');

        $karyawan->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Akun ' . ucfirst($karyawan->role) . ' berhasil dihapus.');
    }
}
