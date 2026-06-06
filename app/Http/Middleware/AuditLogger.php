<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AuditLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::guard('staff')->check() && Schema::hasTable('audit_logs')) {
            $method = $request->method();
            $isMutation = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);

            try {
                AuditLog::create([
                    'user_id' => Auth::guard('staff')->id(),
                    'action' => $isMutation ? 'mutasi_data' : 'akses_halaman',
                    'method' => $method,
                    'url' => $request->fullUrl(),
                    'ip_address' => $request->ip(),
                    'new_values' => $isMutation ? $request->except(['_token', '_method', 'password', 'password_confirmation']) : null,
                    'description' => $isMutation ? 'Perubahan data melalui antarmuka SIMRS.' : 'Akses halaman SIMRS.',
                ]);
            } catch (\Throwable) {
                // Audit tidak boleh menggagalkan transaksi pengguna.
            }
        }

        return $response;
    }
}
