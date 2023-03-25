<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole('seller')) {
            return response()->json(['message' => 'Only sellers have this role
            '], 401);
        }

        return $next($request);
    }
}
