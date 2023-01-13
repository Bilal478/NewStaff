<?php

namespace App\Http\Livewire\Accounts\Teams;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class TeamsShow extends Component
{
    use WithPagination;

    public $teamId;

    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function mount(Team $team)
    {
        $this->teamId = $team->id;
    }

    public function getTeamProperty()
    {
        return Team::find($this->teamId);
    }

    public function render()
    {
        return view('livewire.accounts.teams.show', [
            'team' => Team::find($this->teamId),
        ])->layout('layouts.app', ['title' => 'Team']);
    }

    public function teamTasks()
    {
        return $this->team
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(8);
    }
}
