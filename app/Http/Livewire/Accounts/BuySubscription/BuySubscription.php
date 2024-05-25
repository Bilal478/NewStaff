<?php

namespace App\Http\Livewire\Accounts\BuySubscription;

use App\Models\Account;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BuySubscription extends Component
{
    public $users;
    public $selectedUsers = [];

    public function mount()
    {
        // Retrieve the list of users from your database
        $this->users = User::all();
        $this->selectedUsers = [];
    }
    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }
    public function render()
    {
        return view('livewire.accounts.buy-subscription.buy-subscription', [
			'users' => $this->users
		])->layout('layouts.auth', ['title' => 'Buy Subscription']);
    }
    public function submitForm()
    {
        $user=Auth::user();
        $subscription = DB::table('subscriptions')
		->where('user_id', $user->id)
		->where('stripe_status', 'canceled')
		->first();

        $selectedUserIds = array_keys(array_filter($this->selectedUsers));
        // Get the data for the selected users
        $selectedUserData = User::whereIn('id', $selectedUserIds)->get();
        $selectedUsersCount = count( $selectedUserData);
        foreach($selectedUserIds as $userId){
        $totalUser= DB::table('account_user')->where('user_id', $userId)->get();
        if (count($totalUser)>1) {
            $this->account->users()->detach($userId);
        } 
        else {
            $this->account->users()->detach($userId);
            DB::table('users')->where('id', $userId)->delete();
        }
    }
        return redirect()->intended('billing_page?plan='.$subscription->name.'&usersCount='.$selectedUsersCount);

    }
}
