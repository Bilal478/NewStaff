<?php

namespace App\Http\Livewire\Accounts\Projects;

use App\Models\Subscription;
use App\Models\User;


use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectsIndex extends Component
{
    use WithPagination, AuthorizesRequests, Notifications;

    public $search = '';

    protected $listeners = ['projectsUpdate' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function projectShow(Project $project)
    {
        return redirect()->route('accounts.projects.show', ['project' => $project]);
    }

    public function projectArchive(Project $project)
    {
        if (request()->user()->cannot('delete', $project)) {
            return $this->toast(
                'Unauthorize Action',
                'You don\'t have permission to delete a project.',
                'error'
            );
        }

        $project->delete();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
			return view('livewire.accounts.projects.index', [
				'projects' => $this->projects(),
				])->layout('layouts.app', ['title' => 'Projects']);
    }

    public function projects()
    {
        return Auth::guard('web')->user()->isOwner()
            ? $this->projectsForAccount()
            : $this->projectsForUser();
    }

    public function projectsForAccount()
    {
        return Project::titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users', 'tasks'])
            ->latest()
            ->paginate(12);
    }

    public function projectsForUser()
    {
        $departmentsArray = Auth::guard('web')->user()->departments->pluck('id')->toArray();
        
        return  Project::WhereIn('department_id',$departmentsArray)
            ->with('users:id,firstname,lastname')
            ->titleSearch($this->search)
            ->withCount(['users', 'tasks'])
            ->latest()
            ->paginate(12);
    }
}
