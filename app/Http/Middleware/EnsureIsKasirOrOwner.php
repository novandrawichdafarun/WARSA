<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsKasirOrOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || (!Auth::user()->isKasir() && !Auth::user()->isOwner())) {
            abort(403, 'Anda tidak memiliki hak akses untuk ke halaman ini.');
        }

        return $next($request);
    }
}
