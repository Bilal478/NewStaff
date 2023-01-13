<?php

namespace App\Http\Livewire\Admin\Members;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdminInvitation;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Traits\Notifications;

class MembersIndex extends Component
{
    use WithPagination, Notifications;

    public $search = '';
    public $filter = 'members';

    protected $listeners = [
        'adminInvitationSend' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function adminDelete(Admin $admin)
    {
        if (Admin::count() == 1) {
            return $this->toast(
                'Unauthorize Action',
                'You need at least one admin.',
                'error'
            );
        }

        $admin->forceDelete();
    }

    public function adminInviteDelete(AdminInvitation $adminInvitation)
    {
        $adminInvitation->delete();
    }

    public function render()
    {
        return view('livewire.admin.members.index', [
            'users' => $this->admins(),
        ])->layout('layouts.admin', ['title' => 'Members']);
    }

    public function admins()
    {
        return $this->filter == 'members'
            ? $this->members()
            : $this->invites();
    }

    public function members()
    {
        return Admin::latest()
            ->where('email', 'like', '%' . $this->search . '%')
            ->paginate(8);
    }

    public function invites()
    {
        return AdminInvitation::latest()
            ->where('email', 'like', '%' . $this->search . '%')
            ->paginate(8);
    }
}
