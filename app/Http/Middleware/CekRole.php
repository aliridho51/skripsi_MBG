<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        // If the user's role is in the allowed roles for this route, proceed
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Otherwise, redirect to their proper dashboard
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($userRole === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } elseif ($userRole === 'sekolah') {
            return redirect()->route('sekolah.dashboard');
        }

        return redirect('/');
    }
}