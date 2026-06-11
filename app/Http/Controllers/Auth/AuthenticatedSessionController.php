<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (!$user->hasWarung()) {
            if ($user->isOwner()) {
                return redirect()->route('warung.setup')
                    ->with('info', 'Silakan lengkapi data warung Anda terlebih dahulu.');
            }

            // Jika kasir tidak punya warung, logout otomatis dan beri pesan error
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun kasir Anda belum terhubung ke warung manapun.']);
        }

        if ($user->isOwner()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($user->isKasir() || $user->isPelanggan()) {
            return redirect()->intended(route('pos.index', absolute: false));
        }

        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
