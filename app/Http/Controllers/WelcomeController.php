<?php

namespace App\Http\Controller;
use App\Http\Controllers\Controller;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;


class WelcomeController extends Controller
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function authenticate()
    {
        $this->validate();

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', trans('auth.failed'));

            return;
        }
		$user = Auth::user();
		$user_subscriptions = $user->subscriptions()->active()->get();

		if(empty($user_subscriptions)){
			return redirect()->intended('step2');
		}
		else {
			return redirect()
            ->intended(route(RouteServiceProvider::HOME));
		}
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'Log in']);
    }
}