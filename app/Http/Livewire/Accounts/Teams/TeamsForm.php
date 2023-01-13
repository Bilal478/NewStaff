<?php

namespace App\Http\Livewire\Accounts\Teams;

use App\Http\Livewire\Traits\Notifications;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TeamsForm extends Component
{
    use AuthorizesRequests, Notifications;

    public $team;
    public $isEditing = false;

    protected $listeners = [
        'teamsEdit' => 'edit',
        'teamsCreate' => 'create',
        'projectsUpdate' => '$refresh',
    ];

    protected $rules = [
      'team.title' => 'required|string|max:250',
    ];

    public function create(Team $team)
    {
        if (request()->user()->cannot('create', Team::class)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to create a team.',
                //'error'
            //);
        }

        $this->isEditing = false;
        $this->team = $team;
        $this->showModal();
    }

    public function edit(Team $team)
    {
        if (request()->user()->cannot('update', $team)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to update a team.',
                //'error'
            //);
        }

        $this->isEditing = true;
        $this->team = $team;
        $this->showModal();
    }

    public function showModal()
    {
        $this->dispatchBrowserEvent('open-create-team-modal');
    }

    public function save()
    {
        $this->validate();
        $this->team->save();

        $this->emit('teamsUpdate');
        $this->dispatchBrowserEvent('close-create-team-modal');
        $this->isEditing
            ? $this->toast('Team Updated', "Team has been updated.")
            : $this->toast('Team Created', "Team has been created.");
    }

    public function render()
    {
        return view('livewire.accounts.teams.form');
    }
}
