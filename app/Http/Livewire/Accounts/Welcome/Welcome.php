<?php

namespace App\Http\Livewire\Accounts\Welcome;



use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Welcome extends Component{
	
	public function welcome(){
		$user=Auth::user();
		$user->last_login_at = now();
        $user->last_login_ip = request()->ip();
        $user->save();

        DB::table('access_logs')->insert([
        'user_id'        => $user->id,  
        'action'     => 'user_create',  
        'created_at'     => now(),  
        ]);

        $token = encrypt($user->id);
        $expiry = now()->addDays(30);
        $minutesUntilExpiry = now()->diffInMinutes($expiry); 
        cookie()->queue('auth_token', $token, $minutesUntilExpiry, null, null, false, true);
		session()->forget('account');
		return view('livewire.accounts.welcome.welcome');
	}
}
