<?php

namespace App\Http\Livewire\Admin;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Account;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ActiveMembers extends Component
{
    use WithPagination;

    public $search;
    public $selectedAccount;

    public function render()
    {
        $activeMemberIds = Subscription::where('stripe_status', '!=', 'canceled')->pluck('user_id');
        $accountIds = Account::whereIn('owner_id', $activeMemberIds)->pluck('id')->toArray();
        $userIds = [];

        foreach ($accountIds as $accountId) {
            $userIds = array_merge($userIds, DB::table('account_user')
                ->where('account_id', $accountId)
                ->pluck('user_id')
                ->toArray());
        }
        $userIds = array_unique($userIds);

        $query = User::whereIn('id', $userIds)->with('accounts');
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('created_at', 'like', '%' . $this->search . '%')
                    ->orWhereHas('accounts', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
        if ($this->selectedAccount) {
            $query->whereHas('accounts', function ($query) {
                $query->where('accounts.id', $this->selectedAccount);
            });
        }

        $users = $query->paginate(10);
        $accounts = Account::orderBy('name')->get();

        return view('livewire.admin.active-members', compact('users', 'accounts'))
            ->layout('layouts.admin', ['title' => 'Active Members']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedAccount()
    {
        $this->resetPage();
    }
}