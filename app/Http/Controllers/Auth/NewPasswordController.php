<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (!session('reset_otp_verified') || !session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', session('reset_email'))->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Terjadi kesalahan sistem. Silakan ulangi.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'reset_password_code' => null,
        ])->save();

        session()->forget(['reset_email', 'reset_otp_verified']);

        return redirect()->route('login')->with('status', 'Kata sandi berhasil diubah! Silakan login dengan sandi baru Anda.');
    }
}
