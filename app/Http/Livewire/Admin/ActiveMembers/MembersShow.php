<?php

namespace App\Http\Livewire\Admin\ActiveMembers;

use App\Models\User;
use Livewire\Component;

class MembersShow extends Component
{
    public $userId;
    protected $listeners = [
        'memberShow' => 'show',
    ];
    public function render()
    {
        $accounts=null;
        $user=null;
        $user = User::with('accounts')->find($this->userId);
        if($user){
        $accounts = $user->accounts;
        }
        return view('livewire.admin.active-members.members-show', [
            'user' => $user,
            'accounts' => $accounts,
        ]);
    }
    public function show($userId)
    {
        $this->userId=$userId;
        // dd($this->userId);
        $this->dispatchBrowserEvent('open-member-show');
    }
}
