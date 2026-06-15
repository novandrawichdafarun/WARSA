<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyResetOtpController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-reset-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);

        $user = User::where('email', $request->session()->get('reset_email'))->first();

        if (!$user || $user->reset_password_code !== $request->code) {
            session(['reset_otp_verified' => true]);
        }

        $request->session()->put('reset_otp_verified', true);
        $request->session()->save();

        return redirect()->route('password.reset.form');
    }
}
