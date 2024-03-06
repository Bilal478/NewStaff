<?php

namespace App\Http\Livewire\Auth;

use App\Models\Subscription;
use App\Models\User;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

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

        if (!Auth::guard('web')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', trans('auth.failed'));

            return;
        }
		
        $user = Auth::user();
        $user_subscriptions = $user->subscriptions()->active()->get();

        if (isEmpty($user_subscriptions)) {
            $userAccounts = $user->accounts;
            $ownerIds = $userAccounts
            ->filter(function ($account) {
            return !empty($account->owner_id);
            })
            ->pluck('owner_id');

            foreach ($ownerIds as $ownerId) {

                $owner =DB::table('users')
                ->where('id', $ownerId)
                ->first();	

                if ($owner) {
                    $activeSubscription = DB::table('subscriptions')
                    ->where('user_id', $owner->id)
                    ->where('stripe_status','!=','canceled')
                    ->first();
                }
    
                if ($activeSubscription) {
                    $user->last_login_at = now();
                    $user->last_login_ip = request()->ip();
                    $user->save();
        
                    return redirect()->intended(route(RouteServiceProvider::HOME));
                }
        } 
                Auth::logout();
                $this->addError('email', trans('Subscription has been canceled'));
                return;
        }else {
                // The user has an active subscription
                $user->last_login_at = now();
                $user->last_login_ip = request()->ip();
                $user->save();

                return redirect()->intended(route(RouteServiceProvider::HOME));
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