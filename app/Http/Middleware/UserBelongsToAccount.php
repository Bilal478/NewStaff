<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserBelongsToAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
 
        if ($request->account->hasUser($request->user())) {
            return $next($request);
        }

        return api_response_unauthorized();
    }
}
