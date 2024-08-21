<?php

namespace App\Http\Livewire\Accounts\ManageEmails;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;

class AddCustomEmail extends Component
{
    use Notifications;

    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $account_id;
    public $customUserId;

    protected $listeners = [
        'tasksUpdate' => '$refresh',
        'addCustomEmails' => 'show',
        'customUserDelete' => 'delete', 
    ];

    public function mount()
    {
        $this->account_id = session()->get('account_id');
    }
    public function getAccountProperty()
    {
        return Account::find($this->account_id);
    }
    public function show()
    {
        $this->reset(['email', 'firstname', 'lastname']);
        $this->dispatchBrowserEvent('open-add-custom-email');
    }
    public function save()
    {
        $existingEmail = DB::table('manage_custom_emails')
        ->where('email', $this->email)
        ->where('account_id', $this->account_id)
        ->first();

        if ($existingEmail) {
        $this->toast('Error', 'This email address is already exist.');
        return;
        }
        DB::table('manage_custom_emails')->insert([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'account_id' => $this->account_id,
        ]);   
        $this->emit('tasksUpdate');
        $this->dispatchBrowserEvent('close-add-custom-email');
        $this->toast('Custom User Added', "Custom user has been added.");
        $this->reset(['email', 'firstname', 'lastname']);

    }
    public function delete($id)
    {
        $this->customUserId=$id;
        $customUserRecord = DB::table('manage_custom_emails')->where('id', $this->customUserId)->first();
        if( $customUserRecord){
            DB::table('manage_custom_emails')->where('id', $this->customUserId)->delete();
        }  
        $this->emit('tasksUpdate');
        $this->dispatchBrowserEvent('close-add-custom-email');
        $this->toast('Custom User Deleted', "Custom user has been deleted.");

    }
    public function render()
    {
        return view('livewire.accounts.manage-emails.add-custom-email');
    }
}
