<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = Auth::guard('staff')->user();

        if (! $user || ! $user->hasPermission($module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Izin tidak mencukupi untuk operasi ini.',
                ], 403);
            }

            return back()->with('swal_error', "Akses ditolak untuk {$action} pada modul {$module}.");
        }

        return $next($request);
    }
}
