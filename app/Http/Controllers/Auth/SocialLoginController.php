<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login menggunakan ' . ucfirst($provider)]);
        }

        $user = User::where('email', $socialUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'owner',
                'email_verified_at' => now(),
                $provider . '_id' => $socialUser->getId(),
            ]);
        } else {
            $user->update([
                $provider . '_id' => $socialUser->getId(),
            ]);
        }

        Auth::login($user);

        if ($user->isSuperAdmin()) {
            return redirect()->route('dashboard');
        }

        if (!$user->hasWarung()) {
            if ($user->isOwner()) {
                return redirect()->route('warung.setup')->with('info', 'Silakan lengkapi data warung Anda terlebih dahulu.');
            }
        }

        if ($user->isOwner()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($user->isKasir() || $user->isPelanggan()) {
            return redirect()->route('pos.index');
        }

        return redirect('/');
    }
}
