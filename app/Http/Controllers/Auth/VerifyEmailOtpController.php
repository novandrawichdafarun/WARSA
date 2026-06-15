<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailOtpController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6']
        ]);

        $user = $request->user();

        if ($user->verification_code !== $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi yang Anda masukkan salah.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->update(['verification_code' => null]);

        if ($user->isOwner()) {
            if (!$user->hasWarung()) {
                return redirect()->route('warung.setup')->with('success', 'Email terverifikasi! Silakan lengkapi profil warung Anda.');
            }
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($user->isKasir() || $user->isPelanggan()) {
            return redirect()->intended(route('pos.index', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
