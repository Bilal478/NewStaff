<?php

namespace App\Http\Livewire\Admin\ActiveMembers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Support\Facades\Auth;

class ResetPassword extends Component
{
    use Notifications;
    public $password;
    public $confirm_password;
    public $userId;
    protected $listeners = [
        'resetPassword' => 'show',
    ];
    protected $rules = [
        'password' => 'required|min:8',
        'confirm_password' => 'required|same:password',
    ];
    public function render()
    {
        return view('livewire.admin.active-members.reset-password');
    }
    public  function resetPassword(){
        $authUserId=Auth::user()->id;
        $this->validate();
        $user = User::find($this->userId);
        if ($user) {
            $user->update([
                'password' => Hash::make($this->password),
                'password_reset_by' => $authUserId,
            ]);
        $this->password = '';
        $this->confirm_password = '';
        $this->toast('Reset Password', "Password is reset successfully.");
        $this->dispatchBrowserEvent('close-reset-password');
        }
    }
    public function show($userId){
        $this->userId=$userId;
        $this->dispatchBrowserEvent('open-reset-password');

    }
}
