<?php

namespace App\Http\Livewire\Auth;

use App\Models\Subscription;
use App\Models\User;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $subscriptionExpired = false;

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    // public function authenticate()
    // {
    //     $this->validate();
    
    //     $user = User::where('email', $this->email)->first();
    
    //     if (!$user || !password_verify($this->password, $user->password)) {
    //         $this->addError('email', trans('auth.failed'));
    //         return;
    //     }
    
    //     $user_subscriptions = $user->subscriptions()->active()->get();
    
    //     if (!empty($user_subscriptions) && isset($user_subscriptions[0]) && $user_subscriptions[0]->trial_ends_at < now()) {
    //         // $this->addError('email', trans('Subscription Period Ended'));
    //         $this->subscriptionExpired = true;
    //         return;
    //     }
    
    //     if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
    //         $this->addError('email', trans('auth.failed'));
    //         return;
    //     }
    
    //     $user = Auth::user();
    
    //     $user->last_login_at = now();
    //     $user->last_login_ip = request()->ip();
    //     $user->save();
    
    //     return redirect()->intended(route(RouteServiceProvider::HOME));
    // }
    public function authenticate()
    {
        $this->validate();

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }
		$user = Auth::user();
		$user_subscriptions = $user->subscriptions()->active()->get();

		if(empty($user_subscriptions)){
			return redirect()->intended('step2');
		}
		else {
            $user->last_login_at = now();
            $user->last_login_ip = request()->ip();
            $user->save();
			return redirect()
            ->intended(route(RouteServiceProvider::HOME));
		}
    }
    
    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'Log in']);
    }
    public function buySubscription()
    {
        $currentUser = User::where('email',$this->email)->first();
        $this->validate();

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }
   
        Auth::login($currentUser, true);
        return redirect()->route('buy_subscription');
    }
}