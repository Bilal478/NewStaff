<?php

namespace App\Http\Livewire\Auth;

use App\Mail\AcceptedNotification;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use App\Models\AccountInvitation;

use App\Models\Account;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Login2 extends Component
{
    public $firstname = '';
    public $lastname = '';
    public $password = '';
    public $password_confirmation;
    public $randomid = '';
    public $email = '';
    // public $userexist;
   
    public function update()
    {
        $getUser= DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
        if (!$getUser) {
            abort(404);
        }
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
      $account_invitation = DB::table('account_invitations')->where('email',$user->email)
      ->where('account_id',$getUser->account_id)->first();
      if($account_invitation){
          DB::table('account_invitations')
          ->where('email',$user->email)
          ->where('account_id', $getUser->account_id)
          ->update([
              'invitation_accept' => 'true',
          ]);
      }
      $account_user=DB::table('account_user')->where('account_id',$getUser->account_id)
      ->where('user_id',$getUser->user_id)
      ->first();
      if($account_user){
          DB::table('account_user')
          ->where('account_id', $getUser->account_id)
          ->where('user_id', $getUser->user_id)
          ->update([
              'invitation_accept' => 'false',
          ]);
      }
      $invitationRecord=DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
      if ($invitationRecord) {
        DB::table('verify_invitations')->where('verification_id', $this->randomid)->delete();
    }
      Auth::login($user, true);
      $user->last_login_at = now();
      $user->last_login_ip = request()->ip();
      $user->save();
      
      $token = encrypt($user->id);
      $expiry = now()->addDays(30);
      // $expiry = now()->addMinutes(2);
      $minutesUntilExpiry = now()->diffInMinutes($expiry); 
      cookie()->queue('auth_token', $token, $minutesUntilExpiry, null, null, false, true);    
      return redirect('/dashboard');
    }
    public function mount($randomID)
    {
        $this->randomid = $randomID;
         $getUser= DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
        if($getUser){
         $inviteUser=User::where('id',$getUser->user_id)->first();
         $invitation= DB::table('account_invitations')->where('email',$inviteUser->email)
         ->where('account_id',$getUser->account_id)->first();
         $ownerUser=User::where('id',$invitation->user_id)->first();
      	 Mail::to($ownerUser->email)->send(new AcceptedNotification($inviteUser->email));
        if(!$invitation){
        $deleteInvitation=DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
            if ($deleteInvitation) {
              DB::table('verify_invitations')->where('verification_id', $this->randomid)->delete();
          }
        }
        }
        if (!$getUser || !$invitation) {
            abort(404);
        }
        $user=User::where('id',$getUser->user_id)->first();
        if($user){
        $userExist=$user->multiple_company;
          if ($userExist==true) {
            $account_invitation = DB::table('account_invitations')->where('email',$user->email)
            ->where('account_id',$getUser->account_id)->first();
            if($account_invitation){
                DB::table('account_invitations')
                ->where('email',$user->email)
                ->where('account_id', $getUser->account_id)
                ->update([
                    'invitation_accept' => 'true',
                ]);
            }
            $account_user=DB::table('account_user')->where('account_id',$getUser->account_id)
            ->where('user_id',$getUser->user_id)
            ->first();
            if($account_user){
                DB::table('account_user')
                ->where('account_id', $getUser->account_id)
                ->where('user_id', $getUser->user_id)
                ->update([
                    'invitation_accept' => 'false',
                ]);
            }
            Auth::login($user);
            $invitationRecord=DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
            if ($invitationRecord) {
              DB::table('verify_invitations')->where('verification_id', $this->randomid)->delete();
          }
           $user->last_login_at = now();
           $user->last_login_ip = request()->ip();
           $user->save();
          
           $token = encrypt($user->id);
           $expiry = now()->addDays(30);
           // $expiry = now()->addMinutes(2);
           $minutesUntilExpiry = now()->diffInMinutes($expiry); 
           cookie()->queue('auth_token', $token, $minutesUntilExpiry, null, null, false, true);    

           return redirect()->route('accounts.dashboard');

        }
    }


    }

    public function render()
    {

        $getUserId= DB::table('verify_invitations')->where('verification_id', $this->randomid)->first();
        if (!$getUserId) {
            abort(404);
        }
        $user=DB::table('users')->where('id',$getUserId->user_id)->first();     
        $this->email=$user->email;
        return view('livewire.auth.accept-invitation', ['email' => $this->email])
        ->layout('layouts.auth', ['title' => 'Accept Invitation']);

    }
  
}