<?php

namespace App\Http\Livewire\Accounts\Projects;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectTasks extends Component
{
    use WithPagination;

    public $project;

    protected $listeners = ['tasksUpdate' => '$refresh'];

    public function taskComplete(Task $task)
    {
        $task->update(['completed' => true]);
    }
    public function taskProcessing(Task $task)
    {
        $task->update(['completed' => false]); 
    }

    public function taskDelete(Task $task)
    {
        $task->delete();
    }

    public function render()
    {
        return view('livewire.accounts.projects.tasks', [
            'tasks' => $this->projectTasks(),
        ]);
    }
	public function activityCreate(Task $task){
		
		$this->task_info = $task;
		$this->dispatchBrowserEvent('open-activities-form-modal-2');
		
	}
    public function projectTasks()
    {
        return $this->project
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(5);
    }
}
