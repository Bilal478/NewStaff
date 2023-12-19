<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Http\Livewire\Traits\Notifications;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentTasks extends Component
{
    use WithPagination,Notifications;

    public $department;

    protected $listeners = ['tasksUpdate' => '$refresh'];

    public function taskComplete(Task $task)
    {
        $task->update(['completed' => true]);
        $this->toast('Task Complete', "Task has been updated.");
    }
    public function taskProcessing(Task $task)
    {
        $task->update(['completed' => false]);
        $this->toast('Task In progress', "Task has been updated.");
    }

    public function taskDelete(Task $task)
    {
        $task->delete();
    }

    public function render()
    {
      return view('livewire.accounts.departments.tasks', [
        'tasks' => $this->departmentTasks(),
      ]);
    }

    public function departmentTasks()
    {
        return $this->department
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(5);
    }
}
