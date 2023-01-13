<?php

namespace App\Http\Livewire\Auth\Admin;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';

    public function register()
    {
        $this->validate([
            'firstName' => ['required', 'max:50'],
            'lastName' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:admins'],
            'password' => ['required', 'min:8'],
        ]);

        $admin = Admin::create([
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'admin' => true,
        ]);

        Auth::guard('admin')->login($admin, true);

        return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function render()
    {
        return view('livewire.auth.admin.register')->layout('layouts.auth', ['title' => 'Sign up']);
    }
}
