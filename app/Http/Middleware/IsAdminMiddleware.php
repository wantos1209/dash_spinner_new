<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah pengguna saat ini telah masuk (logged in)
        if (Auth::check()) {
            // Jika pengguna adalah admin, izinkan untuk melanjutkan ke tindakan selanjutnya
            if (Auth::user()->divisi === 'admin') {
                return $next($request);
            }
        }

        // Jika pengguna bukan admin, kembalikan respon berupa pesan akses ditolak
        return response('Unauthorized. You must be an admin to access this.', 403);
    }
}
