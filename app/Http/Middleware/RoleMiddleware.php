<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Check if user has one of the allowed roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access. Your account does not have sufficient permissions.');
        }

        return $next($request);
    }
}
