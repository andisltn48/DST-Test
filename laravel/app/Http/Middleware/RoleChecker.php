<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

use App\Models\User;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $currentUser = User::find(Auth::user()->id);

        foreach ($roles as $key => $value) {
            if ($currentUser->role == $value) {
                return $next($request);
            }
        }
        
        return response()->json([
            'statusCode' => 401,
            'message' => 'Unauthorized',
        ], 401);
    }
}
