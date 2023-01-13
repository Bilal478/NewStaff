<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyAccount
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
        $role = Account::getRoleForUser(Auth::guard('web')->user());
        $currentAccount = Account::find(session()->get('account_id'));

        // Remove member from current account
        if ($role == 'removed') {
            $user = Auth::guard('web')->user();
            Auth::guard('web')->logout();

            session()->forget('account_id');
            session()->forget('account_role');

            $currentAccount->removeMember($user);

            return redirect(route('login'));
        }

        // Update role
        if (session()->get('account_role') != $role) {
            session()->put('account_role', $role);
        }

        return $next($request);
    }
}
