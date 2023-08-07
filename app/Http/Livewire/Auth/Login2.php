<?php

namespace App\Http\Livewire\Auth;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use App\Models\AccountInvitation;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login2 extends Component
{
    public $firstname = '';
    public $lastname = '';
    public $password = '';
    public $password_confirmation;
    public $randomid = '';
    public $email = '';
   
    public function update()
    {
       $this->validate([
            'firstname' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $this->email)->first();
        if ($user) {
            $user->update([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'password' => Hash::make($this->password),
        ]);
      }
      $invitationRecord=DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
      if ($invitationRecord) {
        DB::table('verify_invitations')->where('verification_id', $this->randomid)->delete();
    }
      Auth::login($user, true);
      return redirect('/dashboard');
    }
    public function mount($randomID)
    {
        $this->randomid = $randomID;
    }

    public function render()
    {
        $getUserId= DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
        if (!$getUserId) {
            abort(404);
        }
        $user=User::where('id',$getUserId->user_id)->first();
        $this->email=$user->email;
        return view('livewire.auth.accept-invitation', ['email' => $this->email])
        ->layout('layouts.auth', ['title' => 'Accept Invitation']);

    }
}