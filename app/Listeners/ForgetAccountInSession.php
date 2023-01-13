<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

class ForgetAccountInSession
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->guard == 'web') {
            session()->forget('account_id');
            session()->forget('account_role');
        }
    }
}
