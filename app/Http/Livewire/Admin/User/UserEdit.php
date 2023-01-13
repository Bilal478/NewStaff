<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Livewire\Traits\Notifications;

class UserEdit extends Component
{
    use Notifications;

    public $firstname;
    public $lastname;
    public $email;
    public $password = '';
    public $passwordConfirmation = '';

    public function mount()
    {
        $this->firstname = Auth::guard('admin')->user()->firstname;
        $this->lastname = Auth::guard('admin')->user()->lastname;
        $this->email = Auth::guard('admin')->user()->email;
    }

    public function save()
    {
        $this->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => ['required', 'email', Rule::unique('admins')->ignore(Auth::guard('admin')->user()->id)],
        ]);

        Auth::guard('admin')->user()->update([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
        ]);

        $this->emitTo('admin.user.user-info', 'userProfileUpdated');
        $this->toast('Profile Saved', "The changes to your profile has been saved.");
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:8|same:passwordConfirmation',
        ]);

        Auth::guard('admin')->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->toast('Password Updated', "The password has been updated.");
        $this->reset(['password', 'passwordConfirmation']);
    }

    public function render()
    {
        return view('livewire.admin.profile.edit')
            ->layout('layouts.admin', ['title' => 'My Profile']);
    }
}
