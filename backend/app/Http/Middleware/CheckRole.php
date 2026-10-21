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
        // 1. Jika belum login, kembalikan ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // 2. Jika role cocok, izinkan lewat
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 3. Jika role TIDAK cocok, arahkan ke dashboard role masing-masing (Cegah Loop Redirect)
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($userRole === 'petugas') {
            return redirect()->route('petugas.peminjaman.index');
        }

        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}