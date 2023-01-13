<?php

namespace App\Http\Livewire\Admin;

use App\Models\Account;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'accounts' => $this->getAccounts()
        ])->layout('layouts.admin', ['title' => 'Admin Dashboard']);
    }

    public function getAccounts()
    {
        return Account::query()
            ->with(['users:id,firstname,lastname'])
            ->withCount(['users', 'projects', 'tasks', 'activities',])
            ->latest()
            ->get();
    }
}
