<?php

namespace App\Http\Livewire\Admin\Members;

use Livewire\Component;
use App\Mail\AdminInvite;
use App\Models\AdminInvitation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\Notifications;

class MembersInvite extends Component
{
    use Notifications;

    public $email = '';

    protected $listeners = [
        'adminInvite' => 'show',
    ];

    public function show()
    {
        $this->reset();

        $this->dispatchBrowserEvent('open-invite-modal');
    }

    public function create()
    {
        $validated = $this->validate([
            'email' => ['required', 'email', 'max:100', Rule::unique('admin_invitations')],
        ]);

        $adminInvitation = AdminInvitation::create($validated);

        Mail::to($adminInvitation->email)->send(new AdminInvite($adminInvitation));

        $this->emit('adminInvitationSend');
        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to {$this->email} has been sent.");
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.members.invite');
    }
}
