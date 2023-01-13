<?php

namespace App\Http\Livewire\Accounts\Members;

use App\Models\Account;
use App\Models\Plans;
use Livewire\Component;
use App\Mail\AccountInvite;
use Illuminate\Validation\Rule;
use App\Rules\IsNotMemberOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Rules\AccountInvitationUnique;
use App\Http\Livewire\Traits\Notifications;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembersInvite extends Component
{
    use Notifications;

    public $email = '';
    public $role = 'member';

    protected $listeners = [
        'memberInvite' => 'show',
    ];

    public function show()
    {
        $this->reset(['email', 'role']);

        $this->dispatchBrowserEvent('open-invite-modal');		
    }
	
    public function create()
    {
        $validated = $this->validate([
            'role' => ['required', 'string', Rule::in(['owner', 'manager', 'member'])],
            'email' => ['required', 'email', 'max:100', new AccountInvitationUnique, new IsNotMemberOfAccount],
        ]);

	$validated['user_id'] = Auth::user()->id;

        $accountInvitation = $this->account->invitations()->create($validated);

        Mail::to($accountInvitation->email)
            ->send(new AccountInvite($this->account, $accountInvitation));

        $this->emit('memberInvitationSend');
        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to {$this->email} has been sent.");
        $this->reset(['email', 'role']);
    }

	 public function create2()
    {
     

        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to has been sent.");
       
    }

	
    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }

    public function render()
    {
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
				
        ];
		
        return view('livewire.accounts.members.invite')->with($data);
    }
	
}
