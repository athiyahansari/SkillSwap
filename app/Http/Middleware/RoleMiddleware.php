<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role === $role) {
            return $next($request);
        }

        // Redirect to the user's correct dashboard instead of a hard 403
        return redirect(auth()->user()->dashboardUrl())
            ->with('error', 'You do not have permission to access that area.');
    }
}