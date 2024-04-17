<?php

namespace App\Http\Livewire\Auth;

use App\Mail\TwoFactorVerification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;

class CodeVerification extends Component
{
    public $code;

    protected $rules = [
        'code' => ['required', 'numeric'],
    ];

    public function verify()
    {
        $this->validate();
    
        $user = Auth::user();
    
        if ($user && $user->verification_code == $this->code && $user->verification_code_expiry > now()) {
            $user->verification_code = null;
            $user->verification_code_expiry = null;
            $user->save();
            $this->completeLogin($user);
            $this->clearErrors(); // Add this line
    
        } elseif ($user && $user->verification_code_expiry < now()) {
            $this->sendVerificationCode($user);
            $this->addError('code', trans('Verification code is expired. A new code has been sent.'));
            $this->dispatchBrowserEvent('removeErrorMessage');
            
        } else {
            $this->addError('code', trans('Verification code is invalid.'));
            $this->dispatchBrowserEvent('removeErrorMessage');
        }
    }
    
    public function updated($propertyName)
    {
        if ($propertyName === 'code') {
            $this->clearErrors();
        }
    }
    public function clearErrors()
    {
        $this->resetErrorBag();
    }
    public function completeLogin($user)
    {
        $user->last_login_at = now();
        $user->last_login_ip = request()->ip();
        $user->save();
        
        $token = encrypt($user->id);
        $expiry = now()->addDays(30);
        // $expiry = now()->addMinutes(2);
        $minutesUntilExpiry = now()->diffInMinutes($expiry); 
        cookie()->queue('auth_token', $token, $minutesUntilExpiry, null, null, false, true);    
        return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function render()
    {
        return view('livewire.auth.code-verification')
        ->layout('layouts.auth', ['title' => 'Code Verification']);
    }
    public function sendVerificationCode($user)
    {
        $verificationCode = mt_rand(100000, 999999);
        Mail::to($user->email)->send(new TwoFactorVerification($verificationCode));
        $user->verification_code = $verificationCode;
        $user->verification_code_expiry = now()->addMinutes(1);
        $user->save();
        $this->emit('verificationCodeResent');
    }
}
