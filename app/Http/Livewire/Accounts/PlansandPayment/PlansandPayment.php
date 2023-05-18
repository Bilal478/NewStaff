<?php

namespace App\Http\Livewire\Accounts\PlansandPayment;

use App\Mail\SubscriptionMail;
use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Livewire\Traits\Notifications;
use App\Models\User;
use App\Models\Plans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PlansandPayment extends Component
{
	public $search = '';
    public $filter = 'members';
	
    public function payandcontinue(Request $request){
		
	$user_id = Auth::user()->id;
		
	$trial_verify = DB::select('SELECT * FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status = "canceled"');
	
	$temp = count($trial_verify);

	$user = Auth::user();
    $plan = Plans::where('identifier', $request->plan)->first();

	if($temp > 0){
		
		$request->user()->newSubscription($request->plan, $plan->stripe_id)
			->quantity($request->selectseats)
			->create($request->token, ['email' => $user->email]);
	}else{
		
		$request->user()->newSubscription($request->plan, $plan->stripe_id)
			->trialDays(15)
			->quantity($request->selectseats)
			->create($request->token, 
			[
				'email' => $user->email
			]);	
	}
		
		return redirect()->intended('/sendmail');
	}

    public function index()
    {
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
				
        ];
				
        return view('livewire.accounts.plansandpayment.plansandpayment')->layout('layouts.auth', ['title' => 'Plan Selection'])->with($data);
    }
	
	public function sendMail(){
		
		$user = Auth::user();
		$correo = new SubscriptionMail;
		Mail::to($user->email)->bcc('raul@vndx.com')->send($correo);
		return redirect('thankyou');
	}
}