<?php

namespace App\Http\Livewire\Auth;

use App\Models\Account;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Request;

class Register extends Component
{
    public $accountName = '';
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $captcha = '';

    public function register()
    {
        $this->validate([
            'accountName' => ['required', 'max:100'],
            'firstName' => ['required', 'max:50'],
            'lastName' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'captcha' => ['required', 'captcha'],
        ]);

        $account = Account::create([
            'name' => $this->accountName,
        ]);

        $user = User::create([
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $account->addOwner($user);

        session(['account' => $account->id]);

        Auth::login($user, true);
		
		return redirect()->intended('billing_information?plan=Monthly');

        //return redirect()->intended(route(RouteServiceProvider::HOME));
    }
    public function capthcaFormValidate(Request $request)
    {
        $request->validate([
            'captcha' => 'required | captcha',
        ],
    );
    }
    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth', ['title' => 'Sign up']);
    }
}
