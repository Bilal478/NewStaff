<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class UserIsOwnerOfAccount
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
        if (session()->get('account_role') == 'owner') {
            
            return $next($request);
        }
            $user=Auth::user();
            $permissions_array=explode(',',$user->permissions);
            if(session()->get('account_role') == 'member'){
                $currenturl = url()->current();
                $segments = explode('/', $currenturl);
                $lastSegment = end($segments);
                if(in_array($lastSegment,$permissions_array)){
                    return $next($request);
                }
            }
        return redirect(route(RouteServiceProvider::HOME));
    }
}
