<?php

namespace App\Http\Livewire\Accounts\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectsShow extends Component
{
    use WithPagination;

    public $projectId;

    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function mount(Project $project)
    {
        $this->projectId = $project->id;
    }

    public function getProjectProperty()
    {
        return Project::find($this->projectId);
    }

    public function render()
    {

        return view('livewire.accounts.projects.show', [
            'project' => $this->project,
            'tasks' => $this->projectTasks(),
        ])->layout('layouts.app', ['title' => 'Project']);
    }

    public function projectTasks()
    {
        return $this->project
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(8);
    }
}
