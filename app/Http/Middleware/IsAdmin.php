<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login ATAU bukan admin → tolak dengan 403 Forbidden
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini.');
        }

        return $next($request); // lolos, lanjut ke Controller
    }
}