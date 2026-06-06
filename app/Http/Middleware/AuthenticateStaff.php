<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('staff')->check()) {
            return redirect()->route('login')
                ->with('swal_warning', 'Silakan login untuk mengakses SIMRS.');
        }

        if (! Auth::guard('staff')->user()->is_active) {
            Auth::guard('staff')->logout();

            return redirect()->route('login')
                ->with('swal_error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }

        return $next($request);
    }
}
