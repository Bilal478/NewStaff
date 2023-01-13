<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsShow extends Component
{
    use WithPagination;

    public $departmentId;

    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function mount(Department $department)
    {
        $this->departmentId = $department->id;
    }

    public function getDepartmentProperty()
    {
        return Department::find($this->departmentId);
    }

    public function render()
    {
        return view('livewire.accounts.departments.show', [
            'department' => Department::find($this->departmentId),
        ])->layout('layouts.app', ['title' => 'Department']);
    }

    public function departmentTasks()
    {
        return $this->department
            ->tasks()
            ->with('user:id,firstname,lastname')
            ->latest()
            ->paginate(8);
    }
}
