<?php

namespace App\Http\Livewire\Accounts\Tasks;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use App\Models\Activity;
use App\Models\Department;
use App\Models\Project;
use App\Models\Team;

use App\Models\Task;
use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TasksIndex extends Component
{
    use WithPagination, AuthorizesRequests, Notifications;
	
	public $projects;
	public $start_datetime;
	public $end_datetime;
	public $seconds;
	public $title = '';
	public $seconds_two;
    public $project_id = null;
    public $user_id = '';
	public $datetimerange;
	public $task_info;

    public $search = '';

    protected $listeners = [
        'tasksUpdate' => '$refresh',
        'activityUpdate'=> '$refresh',
        'projectsUpdate' => '$refresh',
		//'activity' => 'activityCreate',
        ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

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
	
	public function activityCreate(Task $task){
		
		$this->task_info = $task;
		$this->dispatchBrowserEvent('open-activities-form-modal-2');
	
	}
	
    public function taskDelete(Task $task)
    {
        $task->delete();
    }

    public function updatingSearch()
    {
        $this->resetPage('taskPage');
    }

    public function render()
    {
        return view('livewire.accounts.tasks.index', [
            'tasks' => $this->tasks(),
            ])->layout('layouts.app', ['title' => 'Tasks']);

    }

    public function tasks()
    {
        return Auth::guard('web')->user()->isOwner()
            ? $this->tasksForAccount()
            : $this->tasksForUser();
    }

    public function tasksForAccount()
    {
        return Task::where('title', 'like', '%' . $this->search . '%')
            ->with('user:id,firstname,lastname')
            ->latest()
           // ->paginate(2);
           ->paginate(8, ['*'], 'taskPage');
    }

    public function tasksForUser()
    {
        $projectsArray = Auth::guard('web')->user()->projects->pluck('id')->toArray();
        $role = Auth::guard('web')->user()->getRole();
        if($role == 'manager'){
            return Task::where('title', 'like', '%' . $this->search . '%')->whereIn('project_id',$projectsArray)
            ->with('user:id,firstname,lastname')
            ->latest()
           // ->paginate(2);
            ->paginate(8, ['*'], 'taskPage');
        }
        else{
            return Auth::guard('web')->user()
            ->tasks()
            ->where('title', 'like', '%' . $this->search . '%')
            ->with('user:id,firstname,lastname')
            ->latest()
           // ->paginate(2);
            ->paginate(8, ['*'], 'taskPage');
        }
    }
}