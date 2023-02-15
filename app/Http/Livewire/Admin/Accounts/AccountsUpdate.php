<?php

namespace App\Http\Livewire\Admin\Accounts;

use Livewire\Component;
use App\Mail\AdminInvite;
use App\Models\AdminInvitation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Account;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AccountsUpdate extends Component
{
    use Notifications;

    public $phone = '';
    public $zipcode = '';
    public $state = '';
    public $city = '';
    public $address = '';
    public $name = '';
    public $accountId = '';
   
    protected $listeners = [
        'updateAccount' => 'show'
    ];
    
    public function show($id)
    {
        $account = Account::where('id',$id)->first();
        $this->accountId = $id;

        $this->name = $account->name;
        $this->address = $account->address;
        $this->city = $account->city;
        $this->state = $account->state;
        $this->zipcode =  $account->zipcode;
        $this->phone = $account->phone;
        
        $this->dispatchBrowserEvent('open-update-modal');
    }

   

    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100']
        ]);
        
        Account::where('id',$this->accountId)->update([
        'name' => $this->name,
        'address' => $this->address,
        'city' => $this->city,
        'state' => $this->state,
        'zipcode' => $this->zipcode,
        'phone' => $this->phone,
    ]);
        
        $this->dispatchBrowserEvent('close-update-modal');
        $this->toast('Account Updated.');
        $this->reset();
        $this->emit('accountsRefresh');
    }

    public function render()
    {
        return view('livewire.admin.accounts.update');
    }
}
