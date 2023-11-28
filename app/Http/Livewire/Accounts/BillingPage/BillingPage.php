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
		
	// $trial_verify = DB::select('SELECT * FROM subscriptions WHERE user_id = "'.$user_id.'"AND stripe_status = "canceled"');
	$trial_verify = DB::select('SELECT * FROM subscriptions WHERE user_id = ? AND trial_ends_at < ?', [$user_id, now()]);

	$temp = count($trial_verify);

	$user = Auth::user();
    $plan = Plans::where('identifier', $request->plan)->first();

	if($temp > 0){
		  // Retrieve the subscription
		  $subscription = $request->user()->subscription($request->plan);
            // Update the quantity if needed
            $subscription->incrementQuantity($request->selectseats);

 // Increase the trial period (e.g., add 15 more days)
 $trialEndDate = now()->addDays(15);
 $subscription->update(['trial_ends_at' => $trialEndDate]);
$subscription->invoice();

// $paymentMethodId = $request->token;

// Update the payment method for the user's subscription
$subscription->updatePaymentMethod($request->token);

// Optionally, retry the last invoice
$user->subscription('your-plan-name')->retryInvoice();

        
		// $request->user()->newSubscription($request->plan, $plan->stripe_id)
		// 	->quantity($request->selectseats)
		// 	->create($request->token, ['email' => $user->email]);

			$subscription=Subscription::where('user_id',$user->id)->first();
			DB::table('transaction_log')->insert([
                'user_id' => $user->id,
				'account_id' => $this->account->id,
                'subscription_id' => $subscription->id,
                'action' => 'subscription_user',
                'quantity' => $request->selectseats,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
	}else{
		$request->user()->newSubscription($request->plan, $plan->stripe_id)
			->trialDays(15)
			->quantity($request->selectseats)
			->create($request->token, 
			[
				'email' => $user->email
			]);	

			$subscription=Subscription::where('user_id',$user->id)->first();
			DB::table('transaction_log')->insert([
                'user_id' => $user->id,
				'account_id' => $this->account->id,
                'subscription_id' => $subscription->id,
                'action' => 'subscription_user',
                'quantity' => $request->selectseats,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
	}
	
		return redirect()->intended('/sendmail');
	}
    public function render()
    {
        $this->usersCount = request('usersCount');
    // dd($this->usersCount );
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
			'userCount' => $this->usersCount
        ];
        return view('livewire.accounts.billing-page.billing-page')
        ->layout('layouts.auth', ['title' => 'Buy Subscription'])->with($data);
    }
    public function sendMail(){
		$emailsRecord = DB::table('registration_email_receivers')->first();
		$user = Auth::user();
		$correo = new SubscriptionMail;
		// Mail::to($user->email)->bcc('raul@vndx.com')->send($correo);
		Mail::to($user->email)->send($correo);
		// if ($emailsRecord) {
		// 	$emails = explode(';', $emailsRecord->email);
		// 	foreach ($emails as $email) {
		// 		Mail::to($email)->send($correo);
		// 	}
		// }
		return redirect('thankyou');
	}
}
