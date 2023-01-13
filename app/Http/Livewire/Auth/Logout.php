<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}
