<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $allowAccess = false;
        foreach ($roles as $role) {
            if ($request->user()->role->name === $role) {
                $allowAccess = true;
                break;
            }
        }

        if (!$allowAccess) {
            return response()->json(['message' => 'Forbidden', 'status' => 403], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
