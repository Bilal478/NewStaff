<?php

namespace App\Http\Livewire\Accounts\BillingPage;

use App\Models\Account;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plans;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use App\Mail\SubscriptionMail;
use Illuminate\Support\Facades\Mail;

class BillingPage extends Component
{
    public $usersCount;
    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }
	
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
            
			$subscription = Subscription::where('user_id', $user->id)
            ->where('stripe_status', 'canceled')
            ->delete();
	}else{
		    $request->user()->newSubscription($request->plan, $plan->stripe_id)
			->quantity($request->selectseats)
			->create($request->token, 
			[
				'email' => $user->email
			]);	
			$subscription = Subscription::where('user_id', $user->id)
            ->where('stripe_status', 'canceled')
            ->delete();
	}
	
		return redirect()->intended('/sendmail');
	}
    public function render()
    {
        $this->usersCount = request('usersCount');
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
			'userCount' => $this->usersCount
        ];
        return view('livewire.accounts.billing-page.billing-page')
        ->layout('layouts.auth', ['title' => 'Billing Page'])->with($data);
    }
    public function sendMail(){
		$emailsRecord = DB::table('registration_email_receivers')->first();
		$user = Auth::user();
		$correo = new SubscriptionMail;
		Mail::to($user->email)->bcc('raul@vndx.com')->send($correo);
		if ($emailsRecord) {
			$emails = explode(';', $emailsRecord->email);
			foreach ($emails as $email) {
				Mail::to($email)->send($correo);
			}
		}
		return redirect('thankyou');
	}
}
