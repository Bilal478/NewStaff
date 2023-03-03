<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetAccountInSession
{
    /**
     * Set the first account of the user and the current role for the account.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->guard == 'web') {
            $account = $event->user->accountsWithRole()->first();
            if(!$account){
                Auth::guard('web')->logout();
                session()->put('account_not_found', true);
                return redirect(route('login'));
            }

            session()->put('account_id', $account->id);
            session()->put('account_role', $account->pivot->role);
        }
    }
}
