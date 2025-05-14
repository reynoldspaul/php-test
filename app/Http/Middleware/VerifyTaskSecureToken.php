<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTaskSecureToken
{
    /**
     * Handle an incoming request. Verify task token is valid.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $task = $request->route('task');

        $token = $request->query('token');

        if (!$task || $task->secure_token !== $token) {
            return response()->json(['message' => 'Unauthorized. Invalid token.'], 403);
        }

        return $next($request);
    }
}
