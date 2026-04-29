<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('admin.login');
        }

        if (! auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        if (! auth()->user()->hasAnyRole(['super-admin', 'admin', 'receptionist'])) {
            abort(403, 'Access denied. You do not have admin privileges.');
        }

        return $next($request);
    }
}
