<?php

namespace App\Http\Livewire\Auth\Admin;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function authenticate()
    {
        $this->validate();

        if (! Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }

        if (! Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password,'is_disabled'=>0])) {
            $this->addError('email', 'Your account is disabled.');

            return;
        }
        return redirect()
            ->intended(route(RouteServiceProvider::ADMIN_HOME));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'Log in']);
    }
}
