<?php

namespace App\Http\Livewire\Accounts\BuySubscription;

use App\Models\Account;
use App\Models\User;
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
        // dd($this->users);
        $this->selectedUsers = $this->users->pluck('id')->toArray();
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
        $selectedUserIds = $this->selectedUsers;
    // dd($selectedUserIds);

    $filteredUserIds = array_filter($selectedUserIds, function ($value) {
        return $value === true;
    });

    $selectedUserIds = array_keys($filteredUserIds);
    // dd($selectedUserIds);
        // Get the data for the selected users
        $selectedUserData = User::whereIn('id', $selectedUserIds)->get();
        // Get the selected users
        // dd($this->selectedUsers);
        // $selectedUsers = User::whereIn('id', $this->selectedUsers)->get();
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
      



        // Redirect to the billing page with selected users
        // return Redirect::route('billing_page', ['users' => $selectedUsers]);
        // return redirect()->route('buy_subscription');
        return redirect()->intended('billing_page?plan=Monthly&usersCount='.$selectedUsersCount);

    }
}
