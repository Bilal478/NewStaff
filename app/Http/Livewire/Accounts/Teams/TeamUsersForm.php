<?php

namespace App\Http\Livewire\Accounts\Teams;

use App\Models\User;
use App\Models\Team;
use Livewire\Component;

class TeamUsersForm extends Component
{
    public $teamId;
    public $userId = '';

    public function getTeamProperty()
    {
        return Team::find($this->teamId);
    }

    public function add()
    {
        $this->team->addMemeber($this->userId);
        $this->reset(['userId']);
    }

    public function remove($userId)
    {
        $this->team->removeMemeber($userId);

        $this->emit('tasksUpdate');
        $this->reset(['userId']);
    }

    public function render()
    {
        return view('livewire.accounts.teams.users-form', [
            'usersIn' => User::inTeam($this->teamId)->orderBy('firstname')->get(),
            'usersOut' => User::notInTeam($this->teamId)->orderBy('firstname')->get(),
        ]);
    }
}
