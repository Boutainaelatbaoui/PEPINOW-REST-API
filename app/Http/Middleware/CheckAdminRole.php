<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->role === 1) {
            return $next($request);
        }

        return response()->json(['message' => 'Only admins have this role'], 401);
        
    }
}
