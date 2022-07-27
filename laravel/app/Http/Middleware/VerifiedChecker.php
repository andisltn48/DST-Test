<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class VerifiedChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUser = User::find(Auth::user()->id);

        
        if ($currentUser->verified == 1) {
            return $next($request);
            
        }
        
        return response()->json([
            'statusCode' => 401,
            'message' => 'Unauthorized',
        ], 401);
    }
}
