<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($request->user());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectAfterVerification($request->user());
    }

    private function redirectAfterVerification(User $user): RedirectResponse
    {
        if ($user->isOwner() && !$user->hasWarung()) {
            return redirect()->route('warung.setup')->with('success', 'Email berhasil diverifikasi! Silakan lengkapi data warung Anda.');
        } elseif ($user->isKasir() && $user->isPelanggan()) {
            return redirect()->route('pos.index')->with('success', 'Email berhasil diverifikasi!');
        }
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
