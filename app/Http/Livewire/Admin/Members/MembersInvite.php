<?php

namespace App\Http\Livewire\Admin\Members;

use Livewire\Component;
use App\Mail\AdminInvite;
use App\Models\AdminInvitation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class MembersInvite extends Component
{
    use Notifications;

    public $email = '';
    public $firstname = '';
    public $lastname = '';
    public $password = '';

    protected $listeners = [
        'addAdmin' => 'show',
    ];

    public function show()
    {
        $this->reset();

        $this->dispatchBrowserEvent('open-create-modal');
    }

   

    public function create()
    {
       
        $this->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('admins')],
            'password' => ['required', 'string','min:6', 'max:100'],
        ]);
        
        $user = Admin::create([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        $this->dispatchBrowserEvent('close-create-modal');
        $this->toast('New Admin Created.');
        $this->reset();
        $this->emit('adminInvitationSend');
    }

    public function render()
    {
        return view('livewire.admin.members.create');
    }
}
