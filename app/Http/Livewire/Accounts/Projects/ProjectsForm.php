<?php

namespace App\Http\Livewire\Accounts\Projects;

use App\Http\Livewire\Traits\Notifications;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ProjectsForm extends Component
{
    use AuthorizesRequests, Notifications;

    public $project;
    public $isEditing = false;

    protected $listeners = [
        'projectEdit' => 'edit',
        'projectCreate' => 'create',
    ];

    protected $rules = [
        'project.title' => 'required|string|max:250',
        'project.description' => 'required|string|max:500',
        'project.category' => 'required|string|max:30',
    ];

    public function create(Project $project)
    {
        // if (request()->user()->cannot('create', Project::class)) {
        //     return $this->toast(
        //         'Unauthorize Action',
        //         'You don\'t have permission to create a project.',
        //         'error'
        //     );
        // }

        $this->isEditing = false;
        $this->project = $project;
        $this->showModal();
    }

    public function edit(Project $project)
    {
        if (request()->user()->cannot('update', $project)) {
            return $this->toast(
                'Unauthorize Action',
                'You don\'t have permission to update a project.',
                'error'
            );
        }

        $this->isEditing = true;
        $this->project = $project;
        $this->showModal();
    }

    public function showModal()
    {
        $this->dispatchBrowserEvent('open-create-modal');
    }

    public function save()
    {
		
        $this->validate();
        $this->project->save();

        $this->emit('projectsUpdate');
        $this->dispatchBrowserEvent('close-create-modal');
        $this->isEditing
        ? $this->toast('Project Updated', "Project has been updated.")
        : $this->toast('Project Created', "Project has been created.");
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.accounts.projects.form');
    }
}
