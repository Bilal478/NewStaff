<?php

namespace App\Http\Livewire\Accounts\ManageEmails;

use Livewire\Component;

class ManageCustomEmails extends Component
{
    public function render()
    {
        return view('livewire.accounts.manage-emails.manage-custom-emails')
        ->layout('layouts.app', ['title' => 'Manage Emails']);
    }
}
