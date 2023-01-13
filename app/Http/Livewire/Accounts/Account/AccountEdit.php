<?php

namespace App\Http\Livewire\Accounts\Account;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\Account;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;

class AccountEdit extends Component
{
    use Notifications;

    public $name;
    public $address;
    public $city;
    public $state;
    public $zipcode;
    public $phone;
    public $punchin_pin_code;

    public function mount()
    {
        $account = Account::find(session()->get('account_id'));

        $this->name = $account->name;
        $this->address = $account->address;
        $this->city = $account->city;
        $this->state = $account->state;
        $this->zipcode = $account->zipcode;
        $this->phone = $account->phone;
        $this->punchin_pin_code = Auth::user()->punchin_pin_code;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|max:50',
            'address' => 'max:150',
            'city' => 'max:70',
            'state' => 'max:50',
            'zipcode' => 'max:20',
            'phone' => 'max:40',
            'punchin_pin_code' => 'max:40',            
        ]);

        $user = Auth::user();
        $user->punchin_pin_code = $this->punchin_pin_code;
        $user->save();

        Account::find(session()->get('account_id'))
            ->update($validated);

        $this->emitTo('accounts.account.account-info', 'accountUpdated');
        $this->toast('Account Saved', "The changes to your account has been saved.");
    }

    public function render()
    {
		$user = Auth::user();

		$user_subscriptions = $user->subscriptions()->active()->get();

		if(empty($user_subscriptions)){
			
			$data = [
            'intent' => auth()->user()->createSetupIntent()
			];
			header("Location: /step2");
			exit();
			// return view('livewire.accounts.plansandpayment.plansandpayment')->layout('layouts.auth', ['title' => 'Plan Selection'])->with($data);
		}
		else{
		
		
		
		
			return view('livewire.accounts.settings.edit')
				->layout('layouts.app', ['title' => 'Settings']);
		}
    }
}
