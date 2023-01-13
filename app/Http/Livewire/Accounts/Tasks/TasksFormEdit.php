<?php

namespace App\Http\Livewire\Accounts\Tasks;

use App\Http\Livewire\Traits\Notifications;
use App\Models\Activity;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TasksFormEdit extends Component
{
    use AuthorizesRequests, Notifications;

    public $projects;
    public $teams;
    public $departments;
    public $users = [];
    public $isEditing = false;
    public $inProject = false;
    public $inTeam = false;
    public $inDepartment = false;

    public $title = '';
    public $description = '';
    public $project_id = null;
    public $team_id = null;
    public $department_id = null;
    public $due_date = null;
    public $user_id = '';
    public $task_id = null;
    public $completed = 'false';
    public $tracking_time = null;
    public $activityId = null;
    public $activities = [];

    protected $listeners = [
        'taskEdit' => 'edit',
    ];

    protected $rules = [
        'title' => 'required|string|max:250',
        'description' => 'required|string|max:500',
        'due_date' => 'date_format:"M d, Y"|nullable',
        'project_id' => 'required',
        'user_id' => 'nullable',
        'team_id' => 'nullable',
        'department_id' => 'nullable',
        'completed' => 'nullable',
    ];

    protected $messages = [
        'project_id.required' => 'The project is required.',
    ];

    public function mount($project = null, $team = null, $department = null)
    {
        $this->projects = Project::orderBy('title')->get(['id', 'title']);
        $this->teams = Team::orderBy('title')->get(['id', 'title']);
        $this->departments = Department::orderBy('title')->get(['id', 'title']);

        if ($project) {
            $this->project_id = $project->id;
            $this->inProject = true;
        }

        if ($team) {
            $this->team_id = $team->id;
            $this->inTeam = true;
        }

        if ($department) {
            $this->department_id = $department->id;
            $this->inDepartment = true;
        }
    }

    public function updatedActivityId()
    {
        $this->tracking_time = gmdate("H:i:s", Activity::find($this->activityId)->seconds);
    }

    public function updatedProjectId()
    {
        $this->users = User::inProject($this->project_id)->get();
        $this->reset(['user_id']);
    }

    public function edit(Task $task)
    {
        $this->isEditing = true;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->due_date = optional($task->due_date)->format('M d, Y');
        $this->project_id = $task->project_id;
        $this->team_id = $task->team_id;
        $this->department_id = $task->department_id;
        $this->user_id = $task->user_id;
        $this->task_id = $task->id;
        $this->completed = $task->completed;
        $this->users = User::inProject($this->project_id)->get();
        $this->activities = Activity::where('task_id', $this->task_id)->get();
        $this->showFormModal();
    }

    public function save()
    {
        $validated = $this->validate();

        $validated['user_id'] = $validated['user_id'] ?: null;
        if ($validated['completed'] == 'true') {
            $validated['completed'] = 1;
        }
        if ($validated['completed'] == 'false') { 
            $validated['completed'] = 0;
        }
        if ($this->isEditing) {
            sscanf($this->tracking_time, "%d:%d:%d", $hours, $minutes, $seconds);
            $time = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
           // Activity::find($this->activityId)->update(['seconds' => $time]);

        }

        $this->isEditing
        ? $this->task->update($validated)
        : Task::create($validated);

        $this->emit('tasksUpdate');
        $this->dispatchBrowserEvent('close-task-form-modal');

        $this->isEditing
        ? $this->toast('Task Updated', "Task has been updated.")
        : $this->toast('Task Created', "Task has been created.");
    }

    public function getTaskProperty()
    {
        return Task::find($this->task_id);
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-task-form-edit-modal');
    }

    public function render()
    {

        return view('livewire.accounts.tasks.form-edit');
    }
}
