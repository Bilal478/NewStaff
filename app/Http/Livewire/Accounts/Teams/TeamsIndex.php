<?php

namespace App\Http\Livewire\Accounts\Teams;

use App\Models\Subscription;
use App\Models\User;


use App\Models\Team;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TeamsIndex extends Component
{
    use WithPagination, AuthorizesRequests, Notifications;

    public $search = '';

    protected $listeners = ['teamsUpdate' => '$refresh','projectsUpdate' => '$refresh',];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function teamShow(Team $team)
    {
        return redirect()->route('accounts.teams.show', ['team' => $team]);
    }

    public function teamArchive(Team $team)
    {
        if (request()->user()->cannot('delete', $team)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to delete a team.',
                //'error'
            //);
        }

        $team->delete();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
		  return view('livewire.accounts.teams.index', [
			'teams' => $this->teams(),
		  ])->layout('layouts.app', ['title' => 'Teams']);
    }

    public function teams()
    {
        return Auth::guard('web')->user()->isOwnerOrManager()
            ? $this->teamsForAccount()
            : $this->teamsForUser();
    }

    public function teamsForAccount()
    {
        return Team::titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users'])
            ->orderBy('title')
            ->paginate(12);
    }

    public function teamsForUser()
    {
        return Auth::guard('web')->user()
            ->teams()
            ->titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users'])
            ->latest()
            ->paginate(12);
    }
}
