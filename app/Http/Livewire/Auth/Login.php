<?php

namespace App\Http\Livewire\Auth;

use App\Mail\TwoFactorVerification;
use App\Models\Subscription;
use App\Models\User;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
        $userAccounts = $user->accounts;
        $ownerIds = $userAccounts
        ->filter(function ($account) {
        return !empty($account->owner_id);
        })
        ->pluck('owner_id');
        $ownerIds[]=$user->id;
        $isSubscriptionExist=Subscription::whereIn('user_id',$ownerIds)->get();
        $account=Account::where('owner_id',$user->id)->first();
        if($isSubscriptionExist->isEmpty()){
            session(['account' => $account->id]);
            
            Auth::login($user, true);
            
            return redirect()->intended('billing_information?plan=Monthly');
        }
        $user_subscriptions = $user->subscriptions()->active()->get();

        if ($user_subscriptions->isEmpty()) {
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
                    $this->sendVerificationCode($user);
                    Session::put('2fa_user_id', $user->id); // Store user ID in session for verification
                    $this->redirect('/verify-2fa'); // Redirect to 2FA verification page
                    // $user->last_login_at = now();
                    // $user->last_login_ip = request()->ip();
                    // $user->save();
        
                    // return redirect()->intended(route(RouteServiceProvider::HOME));
                }
        } 
                Auth::logout();
                $this->addError('email', trans('Subscription has been canceled'));
                return;
        }else {
            $this->sendVerificationCode($user);
            Session::put('2fa_user_id', $user->id); // Store user ID in session for verification
            $this->redirect('/verify-2fa'); // Redirect to 2FA verification page
                // // The user has an active subscription
                // $user->last_login_at = now();
                // $user->last_login_ip = request()->ip();
                // $user->save();

                // return redirect()->intended(route(RouteServiceProvider::HOME));
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
    public function sendVerificationCode($user)
    {
        $verificationCode = mt_rand(100000, 999999);
        Mail::to($user->email)->send(new TwoFactorVerification($verificationCode));

        $user->verification_code = $verificationCode;
        $user->verification_code_expiry = now()->addMinutes(1);
        $user->save();
    }
}