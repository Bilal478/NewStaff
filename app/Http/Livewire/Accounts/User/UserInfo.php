<?php

namespace App\Http\Livewire\Accounts\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserInfo extends Component
{
    public $avatar = null;

    protected $listeners = ['userProfileUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.accounts.profile.info', [
            'user' => Auth::guard('web')->user(),
        ]);
    }
}
