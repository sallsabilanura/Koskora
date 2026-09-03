<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     * Blocks pending or rejected users from accessing the main application.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Superadmins always have access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Block rejected users
        if ($user->isRejected()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah ditolak. Silakan hubungi administrator.');
        }

        // Redirect pending users to waiting page
        if ($user->isPending()) {
            // Allow access to pending page and logout
            if ($request->routeIs('pending.approval') || $request->routeIs('logout') || $request->routeIs('profile.edit') || $request->routeIs('profile.update') || $request->routeIs('password.update')) {
                return $next($request);
            }
            return redirect()->route('pending.approval');
        }

        return $next($request);
    }
}
