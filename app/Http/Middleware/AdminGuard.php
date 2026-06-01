<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminGuard
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user login via guard 'admin' dan memiliki role 'admin'
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin') {
            return $next($request);
        }

        // Jika tidak, redirect ke halaman login admin
        return redirect('/admin/login');
    }
}