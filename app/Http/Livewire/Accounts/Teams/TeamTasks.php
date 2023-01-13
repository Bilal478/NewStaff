<?php

namespace App\Http\Livewire\Accounts\Teams;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class TeamTasks extends Component
{
    use WithPagination;

    public $team;

    protected $listeners = ['tasksUpdate' => '$refresh','projectsUpdate' => '$refresh',];

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
      return view('livewire.accounts.teams.tasks', [
        'tasks' => $this->teamTasks(),
      ]);
    }

    public function teamTasks()
    {
        return $this->team
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(5);
    }
}
