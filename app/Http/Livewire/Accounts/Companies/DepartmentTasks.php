<?php

namespace App\Http\Livewire\Companies\Departments;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentTasks extends Component
{
    use WithPagination;

    public $department;

    protected $listeners = ['tasksUpdate' => '$refresh'];

    public function taskComplete(Task $task)
    {
        $task->update(['completed' => true]);
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
