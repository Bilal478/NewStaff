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
		$cancel_subscription  = $user->subscription($_GET['planname'])->cancelNow();
		
		return redirect()->intended('https://neostaff.app/#price');
	}
	
	public function deleteseats(Request $request){
		
		
		$user = Auth::user();
		
		$number_seats = $_POST['all-seats'];
			
		if($number_seats==1){
			$user->subscription($_POST['planname'])->decrementQuantity(1);
		}
		if($number_seats>1){
			$user->subscription($_POST['planname'])->decrementQuantity($number_seats);
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
	public function payandcontinue(Request $request){
		
		$user = Auth::user();
	
		if ($user->hasDefaultPaymentMethod()) {
			
			$user->subscription($request->plan)
				->incrementQuantity($request->selectseats);
		}
		
		return redirect()->intended("/billing?buy_more_seats="."$request->selectseats");	
	}
}