<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            abort(403, 'Unauthorized - Not Logged In');
        }

        // Ambil user login
        $user = Auth::user();

        // Pastikan user punya relasi role
        if (!$user->role || $user->role->name !== $role) {
            abort(403, 'Unauthorized - No Proper Role');
        }

        return $next($request);
    }
}
