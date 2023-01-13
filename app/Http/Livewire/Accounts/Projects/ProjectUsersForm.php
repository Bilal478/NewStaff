<?php

namespace App\Http\Livewire\Accounts\Projects;

use App\Models\User;
use App\Models\Project;
use Livewire\Component;

class ProjectUsersForm extends Component
{
    public $projectId;
    public $userId = '';

    public function getProjectProperty()
    {
        return Project::find($this->projectId);
    }

    public function add()
    {
        $this->project->addMemeber($this->userId);
        $this->reset(['userId']);
		
		return redirect('/projects/'.$this->projectId);
    }

    public function remove($userId)
    {
        $this->project->removeMemeber($userId);
        $this->project->tasks()->where('user_id', $userId)->update(['user_id' => null]);

        $this->emit('tasksUpdate');
        $this->reset(['userId']);
		
		return redirect('/projects/'.$this->projectId);
    }

    public function render()
    {
        return view('livewire.accounts.projects.users-form', [
            'usersIn' => User::inProject($this->projectId)->orderBy('firstname')->get(),
            'usersOut' => User::notInProject($this->projectId)->orderBy('firstname')->get(),
        ]);
    }
}
