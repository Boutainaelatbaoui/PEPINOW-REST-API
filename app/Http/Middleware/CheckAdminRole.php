<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'Only admins have this role'], 401);
        }

        return $next($request);
    }
}
