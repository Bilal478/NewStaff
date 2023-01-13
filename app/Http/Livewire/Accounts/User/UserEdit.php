<?php

namespace App\Http\Livewire\Accounts\User;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Support\Facades\DB;

class UserEdit extends Component
{
    use Notifications;

    public $firstname;
    public $lastname;
    public $email;
    public $punchin_pin_code = '';
    public $password = '';
    public $passwordConfirmation = '';

    public function mount()
    {
        $this->firstname = Auth::guard('web')->user()->firstname;
        $this->lastname = Auth::guard('web')->user()->lastname;
        $this->email = Auth::guard('web')->user()->email;
        $this->punchin_pin_code = Auth::guard('web')->user()->punchin_pin_code;
    }

    public function save()
    {
        $this->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::guard('web')->user()->id)],
        ]);

    
        //get the company id
        $account= DB::table('account_user')
                ->where('user_id','=',Auth::guard('web')->user()->id)
                ->get()->first();
        $account_id=$account->account_id;

        //Validate if punching code is used by other user in the company
        $data = DB::table('users')
            ->join('account_user', 'account_user.user_id', '=', 'users.id')
            ->where('users.punchin_pin_code', $this->punchin_pin_code)
            ->where('account_user.account_id', $account_id . '')
            ->where('users.id', '<>', Auth::guard('web')->user()->id)
            ->get();
            
            if(count($data)>0)
            {
                $this->toast('Error', "Punch in pin code is invalid write another one.",'error',4000);
            }else
            {
                Auth::guard('web')->user()->update([
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'email' => $this->email,
                    'punchin_pin_code' => $this->punchin_pin_code,
                ]);
        
                $this->emitTo('accounts.user.user-info', 'userProfileUpdated');
                $this->toast('Profile Saved', "The changes to your profile has been saved.");
            }
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:8|same:passwordConfirmation',
        ]);

        Auth::guard('web')->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->toast('Password Updated', "The password has been updated.");
        $this->reset(['password', 'passwordConfirmation']);
    }

    public function render()
    {
        return view('livewire.accounts.profile.edit')
            ->layout('layouts.app', ['title' => 'My Profile']);
    }
}
