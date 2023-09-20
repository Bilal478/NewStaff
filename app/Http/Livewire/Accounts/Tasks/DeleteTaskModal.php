<?php

namespace App\Http\Livewire\Accounts\Tasks;

use App\Models\Project;
use App\Models\User;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Department;

class DeleteTaskModal extends Component
{
    use Notifications;
    public $userId;
    public $users;
    public $selectedOption='firstOption';
    public $userFirstName;
    public $userFullName;
    public $selectedUserId;
    public $projectId;
    public $departmentId;

    protected $listeners = [
        'remove' => 'removeMember', 
        'removeDepartmentMember' => 'removeFromDepartment'
    ];

    public function getProjectProperty()
    {
        return Project::find($this->projectId);
    }
    public function getDepartmentProperty()
    {
        return Department::find($this->departmentId);
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

    public function removeFromDepartment($userId,$users,$departmentId)
    {
        $this->userId=$userId;
        $this->departmentId = $departmentId;
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
        if($this->departmentId){
            if($this->selectedOption=='firstOption'){
                $this->department->tasks()->where('user_id', $this->userId)->update(['user_id' => null]);
                $this->department->removeMemeber($this->userId);
                $this->dispatchBrowserEvent('close-delete-task-modal');
            }
            else
            {
                
                $this->department->tasks()->where('user_id', $this->userId)->update(['user_id' => $this->selectedUserId]);
                $this->department->removeMemeber($this->userId);
                $this->dispatchBrowserEvent('close-delete-task-modal');
            }
        return redirect('/departments/'.$this->departmentId);

        }
        else{
            if($this->selectedOption=='firstOption'){
                $this->project->tasks()->where('user_id', $this->userId)->update(['user_id' => null]);
                $this->project->removeMemeber($this->userId);
                $this->dispatchBrowserEvent('close-delete-task-modal');
            }
            else
            { 
                $this->project->tasks()->where('user_id', $this->userId)->update(['user_id' => $this->selectedUserId]);
                $this->project->removeMemeber($this->userId);
                $this->dispatchBrowserEvent('close-delete-task-modal');
            }
        return redirect('/projects/'.$this->projectId);
        }
    }
}