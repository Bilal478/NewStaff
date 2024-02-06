<?php

namespace App\Http\Livewire\Accounts\Billing;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AccountInvitation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;


class BillingInfo extends Component
{
		public $seats_available=0;
		public $search = '';
		public $filter = 'members';
		public $price_Annual=36;
		public $price_Monthly =4;
			
     public function render()
    {
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
				
        ];
		
		$user = Auth::user();
	
		$user_subscriptions = $user->subscriptions()->active()->get();

		return view('livewire.accounts.billing.billinginfo',  ['subscriptions'=>$user_subscriptions, 'users' => $this->users()])->layout('layouts.app', ['title' => 'Billing'])
		->with($data);
    }
	
	public function cancel2()
	{
		$user = Auth::user();
		$subscription=$user->subscription($_GET['planname']);
		$cancel_subscription  = $user->subscription($_GET['planname'])->cancelNow();
		DB::table('transaction_log')->insert([
			'user_id' => $user->id,
			'account_id' => $this->account->id,
			'subscription_id' => $subscription->id,
			'action' => 'cancel_subscription',
			'quantity' => $subscription->quantity,
			'created_at' => now(),
			'updated_at' => now(),
		]);
		
		return redirect()->intended('https://neostaff.app/#price');
	}
	
	public function deleteseats(Request $request){
		
		$user = Auth::user();
		$subscription=$user->subscription($_POST['planname']);
		
		$number_seats = $_POST['all-seats'];
			
		if($number_seats==1){
			$user->subscription($_POST['planname'])->decrementQuantity(1);
			DB::table('transaction_log')->insert([
				'user_id' => $user->id,
				'account_id' => $this->account->id,
				'subscription_id' => $subscription->id,
				'action' => 'delete_seats',
				'quantity' => 1,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
		if($number_seats>1){
			$user->subscription($_POST['planname'])->decrementQuantity($number_seats);
			DB::table('transaction_log')->insert([
				'user_id' => $user->id,
				'account_id' => $this->account->id,
				'subscription_id' => $subscription->id,
				'action' => 'delete_seats',
				'quantity' => $number_seats,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}

		return redirect()->intended("/billing");
	}
	
	public function users()
    {
        return $this->filter == 'members'? $this->members(): $this->invites();
    }

    public function members()
    {
		$account = Account::find(session()->get('account_id'));
        return $account
            ->usersWithRole()
            ->where('role', '!=', 'removed')
            ->latest()
            ->where(function (Builder $query) {
                return $query->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
        });
    }

    public function invites()
    {
        return AccountInvitation::latest()
            ->where('email', 'like', '%' . $this->search . '%');
    }
	public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }

// 	public function payandcontinue(Request $request) {
// 		Stripe::setApiKey(config('services.stripe.secret'));
//         $user = Auth::user();
//         $subscription = $user->subscription($request->plan);
// 	    $price = \Stripe\Price::retrieve($subscription->stripe_price);
// 	    // $amount = $price->unit_amount * $request->selectseats;
// 		$amountPerSeat = $price->unit_amount;
// // dd($user->subscribed($request->plan));
// 		// Check if the user is still in the trial period
// 		if ($user->subscribed($request->plan)) {
// 			// dd('if');

// 			// Calculate the remaining days in the trial period
// 			$remainingTrialDays =  $subscription->trial_ends_at->diffInDays(now());

// 			// Calculate the monthly amount per seat
// 			$monthlyAmountPerSeat = $amountPerSeat / 30; // Assuming 30 days in a month
	
// 			// Calculate the amount based on the remaining trial days
// 			$amount = $monthlyAmountPerSeat * $remainingTrialDays * $request->selectseats;
// 	dd( $amount);

// 		} else {
// 			dd('else');
// 			// User is not in the trial period, use the regular calculation
// 			$amount = $amountPerSeat * $request->selectseats;
// 		}
// 	dd('ddd');
// 		if ($user->hasDefaultPaymentMethod()) {
// 			try {
// 				$paymentIntent = PaymentIntent::create([
// 					'payment_method' => $user->defaultPaymentMethod()->id,
// 					'amount' => $amount,
// 					'currency' => 'usd', // replace with your currency code (e.g., USD)
// 					'customer' => $user->stripe_id,
// 					'off_session' => true,
// 					'confirm' => true,
// 				]);

// 				$user->subscription($request->plan)->incrementQuantity($request->selectseats);
	
// 				// Log the payment information or perform additional actions as needed
// 				DB::table('transaction_log')->insert([
// 					'user_id' => $user->id,
// 					'account_id' => $this->account->id,
// 					'subscription_id' => $subscription->id,
// 					'payment_intent_id' => $paymentIntent->id,
// 					'action' => 'buy_seats',
// 					'quantity' => $request->selectseats,
// 					'created_at' => now(),
// 					'updated_at' => now(),
// 				]);
	
// 				// Redirect with a success message or to a specific page
// 				return redirect()->intended("/billing?buy_more_seats=" . $request->selectseats);
// 			} catch (\Exception $e) {
// 				// Handle the payment error
// 				return redirect()->back()->with('error', $e->getMessage());
// 			}
// 		}
// 	}
    public function payandcontinue(Request $request){

	    $user = Auth::user();
	    $subscription=$user->subscription($request->plan);
	    if ($user->hasDefaultPaymentMethod()) {

		    $user->subscription($request->plan)
			->incrementQuantity($request->selectseats);
			DB::table('transaction_log')->insert([
				'user_id' => $user->id,
				'account_id' => $this->account->id,
				'subscription_id' => $subscription->id,
				'action' => 'buy_seats',
				'quantity' => $request->selectseats,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
		return redirect()->intended("/billing?buy_more_seats="."$request->selectseats");	
	}
	

}