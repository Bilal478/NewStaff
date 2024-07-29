<?php

namespace App\Http\Livewire\Accounts\ManageEmails;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;

class EditCustomEmail extends Component
{
    use Notifications;
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $customUserId;
    protected $listeners = [
        'customUserEdit' => 'edit', 
    ];
    public function edit($id)
    {
        $this->customUserId=$id;
        $customUserRecord = DB::table('manage_custom_emails')->where('id', $this->customUserId)->first();
        $this->firstname=$customUserRecord->firstname;
        $this->lastname=$customUserRecord->lastname;
        $this->email=$customUserRecord->email;
        $this->dispatchBrowserEvent('open-edit-custom-email');
    }
    public function update()
    {
        DB::table('manage_custom_emails')
                ->where('id', $this->customUserId)
                ->update([
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'email' => $this->email,
                ]);
                $this->emit('tasksUpdate');
                $this->dispatchBrowserEvent('close-edit-custom-email');
                $this->toast('Custom User Updated', "Custom user has been updated.");
    }
    public function render()
    {
        return view('livewire.accounts.manage-emails.edit-custom-email');
    }
}
