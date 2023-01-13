<?php

namespace App\Http\Livewire\Auth;

use App\Mail\AcceptedNotification;
use App\Models\User;
use App\Rules\Equals;
use App\Models\Account;
use Livewire\Component;
use App\Scopes\AccountScope;
use Illuminate\Http\Request;
use App\Models\AccountInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Invitation extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $invitationId = null;
	

    public function mount(Request $request, $accountInvitation)
    {
        // Signature invalid
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $invitation = AccountInvitation::withoutGlobalScope(AccountScope::class)->findOrFail($accountInvitation);

        $this->email = $invitation->email;
        $this->invitationId = $invitation->id;
	Mail::to($invitation->user->email)->send(new AcceptedNotification($this->email));

        // User logged in but not the one for the invitation
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->email != $invitation->email) {
            abort(401);
        }

        // User logged in and the one for the invitation
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->email == $invitation->email) {
            $this->account
                ->users()
                ->attach([
                    Auth::guard('web')->user()->id => ['role' => $invitation->role]
                ]);
            $invitation->delete();

            return redirect(route(RouteServiceProvider::HOME));
        }

        // User has an account but not logged in
        if (User::where('email', $this->email)->exists()) {
            return redirect(route('login'));
        }
    }

    public function register()
    {
        $this->validate([
            'firstName' => ['required', 'max:50'],
            'lastName' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users', new Equals($this->accountInvitation->email)],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'firstname' => $this->firstName,
            'lastname' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $this->account
            ->users()
            ->attach([
                $user->id => ['role' => $this->accountInvitation->role]
            ]);

        $this->accountInvitation->delete();
        Auth::login($user, true);

        return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function getAccountProperty()
    {
        return Account::find($this->accountInvitation->account_id);
    }

    public function getAccountInvitationProperty()
    {
        return AccountInvitation::withoutGlobalScope(AccountScope::class)
            ->find($this->invitationId);
    }

    public function render()
    {
        return view('livewire.auth.invitation')
            ->layout('layouts.auth', ['title' => 'Account Invitation']);
    }
}
