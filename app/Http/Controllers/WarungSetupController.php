<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WarungSetupController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::user()->hasWarung()) {
            return redirect()->route('dashboard');
        }
        return view('warung.setup');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_warung' => ['required', 'string', 'max:255', 'unique:warung,nama_warung'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_qris_active' => ['nullable', 'boolean'],
            'qris_string' => ['nullable', 'string'],
        ]);

        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : null;

        $warung = Warung::create([
            'nama_warung' => $request->nama_warung,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'logo' => $request->file('logo')->store('logos', 'public'),
            'is_qris_active' => $request->is_qris_active || false,
            'qris_string' => $request->qris_string,
        ]);

        Auth::user()->update(['warung_id' => $warung->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Warung berhasil dibuat! Selamat datang di WARSA.');
    }
}
