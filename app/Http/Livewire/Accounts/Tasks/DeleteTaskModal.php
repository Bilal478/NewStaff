<?php

namespace App\Http\Livewire\Accounts\Tasks;

use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class DeleteTaskModal extends Component
{
    public $userId;
    public $users;
    public $selectedOption;
    public $userFirstName;
    public $userFullName;
    public $selectedUserId;
    public $projectId;

    protected $listeners = [
        'remove' => 'removeMember', 
    ];

    public function getProjectProperty()
    {
        return Project::find($this->projectId);
    }
    public function render()
    {
        return view('livewire.accounts.tasks.delete-task-modal');
    }
    public function removeMember($userId,$users,$projectId)
    {
        $this->userId=$userId;
        $this->projectId = $projectId;
        $this->users=$users;
 
        foreach ($this->users as $key => $user) {
            if ($user['id'] == $this->userId) {
               unset($this->users[$key]);
               break; // Stop the loop after removing the user
            }
        }

        $user=User::where('id',$this->userId)->first();
        $this->userFirstName=$user->firstname;
        $this->userFullName=$user->firstname.' '.$user->lastname;
        $this->dispatchBrowserEvent('open-delete-task-modal');

    }
    public  function save(){
        if($this->selectedOption=='firstOption'){
            $this->project->removeMemeber($this->userId);
            $this->project->tasks()->where('user_id', $this->userId)->update(['user_id' => null]);
            $this->dispatchBrowserEvent('close-delete-task-modal');
            return redirect('/projects/'.$this->projectId);
        }
        else
        {
            
            $this->project->tasks()->where('user_id', $this->userId)->update(['user_id' => $this->selectedUserId]);
            $this->project->removeMemeber($this->userId);
            $this->dispatchBrowserEvent('close-delete-task-modal');
            return redirect('/projects/'.$this->projectId);
        }
    }
}