<?php

namespace App\Http\Livewire\Admin;

use App\Models\Subscription;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Component;

class ActiveMembers extends Component
{
    use WithPagination;
    public $search;
    public function render()
    {
        $activeMembers = Subscription::where('stripe_status', 'active');

        if ($this->search) {
            $activeMembers->where(function($query) {
                $query->whereHas('user', function($subQuery) {
                    $subQuery->where('firstname', 'like', '%' . $this->search . '%')
                             ->orWhere('lastname', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%')
                             ->orWhere('created_at', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user.accounts', function($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        $activeMembers = $activeMembers->paginate(10);
    
        // Fetch user data and associated accounts based on user_id
        $userData = $activeMembers->map(function ($subscription) {
            $user = User::find($subscription->user_id);
            $accounts = $user ? $user->accounts : [];
            
            return [
                'user_data' => $user,
                'accounts_data' => $accounts,
            ];
        });
        return view('livewire.admin.active-members', compact('userData', 'activeMembers'))
            ->layout('layouts.admin', ['title' => 'Active Members']);
    }
    
}
