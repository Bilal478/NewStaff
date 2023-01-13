<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserInfo extends Component
{
    public $avatar = null;

    protected $listeners = ['userProfileUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.admin.profile.info', [
            'user' => Auth::guard('admin')->user(),
        ]);
    }
}
