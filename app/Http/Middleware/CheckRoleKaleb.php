<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleKaleb
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the user has the role of 'kaleb'
        if (Auth::user()->role !== 'kaleb') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
