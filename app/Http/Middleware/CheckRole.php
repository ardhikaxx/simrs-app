<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::guard('staff')->user();

        if (! $user) {
            return redirect()->route('login')
                ->with('swal_warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        if (! $user->hasAnyRole($roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
