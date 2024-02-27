<?php

namespace App\Http\Livewire\Auth;

use App\Models\Subscription;
use App\Models\User;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required']
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
            $user->last_login_at = now();
            $user->last_login_ip = request()->ip();
            $user->save();
			return redirect()
            ->intended(route(RouteServiceProvider::HOME));
		}
    }

    public function render()
    {
        return view('livewire.auth.home') ->layout('layouts.auth', ['title' => 'NeoStaff - Home']);
    }
}