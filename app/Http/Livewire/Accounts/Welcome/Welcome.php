<?php

namespace App\Http\Livewire\Accounts\Welcome;



use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;


class Welcome extends Component{
	
	public function welcome(){
		session()->forget('account');
		return view('livewire.accounts.welcome.welcome');
	}
}
