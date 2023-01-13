<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Models\Subscription;
use App\Models\User;


use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DepartmentsIndex extends Component
{
    use WithPagination, AuthorizesRequests, Notifications;

    public $search = '';

    protected $listeners = ['departmentsUpdate' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function departmentShow(Department $department)
    {
        return redirect()->route('accounts.departments.show', ['department' => $department]);
    }

    public function departmentArchive(Department $department)
    {
        if (request()->user()->cannot('delete', $department)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to delete a department.',
                //'error'
            //);
        }

        $department->delete();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
	
			return view('livewire.accounts.departments.index', [
				'departments' => $this->departments(),
				])->layout('layouts.app', ['title' => 'Departments']);
			
    }

    public function departments()
    {
        return Auth::guard('web')->user()->isOwnerOrManager()
            ? $this->departmentsForAccount()
            : $this->departmentsForUser();
    }

    public function departmentsForAccount()
    {
        return Department::titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users'])
            ->orderBy('title')
            ->paginate(12);
    }

    public function departmentsForUser()
    {
        return Auth::guard('web')->user()
            ->departments()
            ->titleSearch($this->search)
            ->with('users:id,firstname,lastname')
            ->withCount(['users'])
            ->latest()
            ->paginate(12);
    }
}
