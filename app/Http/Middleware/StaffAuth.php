<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAuth
{
    /**
     * Handle an incoming request.
     * Redirects unauthenticated users to /login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('staff_logged_in')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
