<?php

namespace App\Http\Livewire\Accounts\Account;

use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\Notifications;

class AccountCreate extends Component
{
    use Notifications;

    public $name = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zipcode = '';
    public $phone = '';

    public function create()
    {
        $validated = $this->validate([
            'name' => 'required|max:50',
            'address' => 'max:150',
            'city' => 'max:70',
            'state' => 'max:50',
            'zipcode' => 'max:20',
            'phone' => 'max:40',
        ]);

        $account = Account::create($validated);
        $account->addOwner(Auth::guard('web')->user());

        $this->emitTo('accounts.account.account-info', 'accountCreated', [
            'id' => $account->id,
        ]);
    }

    public function render()
    {
        return view('livewire.accounts.account.create')
            ->layout('layouts.app', ['title' => 'New Company']);
    }
}
