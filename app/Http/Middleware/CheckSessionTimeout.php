<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    private int $timeout = 1800;

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check()) {
            $lastActivity = session('last_activity_time');

            if ($lastActivity && (time() - $lastActivity) > $this->timeout) {
                Auth::guard('staff')->logout();
                session()->invalidate();
                session()->regenerateToken();

                return redirect()->route('login')
                    ->with('swal_warning', 'Sesi otomatis diakhiri karena tidak aktif selama 30 menit.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
