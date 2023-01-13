<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Models\Department;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DepartmentsForm extends Component
{
    use AuthorizesRequests, Notifications;

    public $department;
    public $isEditing = false;

    protected $listeners = [
        'departmentsEdit' => 'edit',
        'departmentsCreate' => 'create',
    ];

    protected $rules = [
        'department.title' => 'required|string|max:250',
    ];

    public function create(Department $department)
    {
        if (request()->user()->cannot('create', Department::class)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to create a department.',
                //'error'
            //);
        }

        $this->isEditing = false;
        $this->department = $department;
        $this->showModal();
    }

    public function edit(Department $department)
    {
        if (request()->user()->cannot('update', $department)) {
            //return $this->toast(
                //'Unauthorize Action',
                //'You don\'t have permission to update a department.',
                //'error'
            //);
        }

        $this->isEditing = true;
        $this->department = $department;
        $this->showModal();
    }

    public function showModal()
    {
        $this->dispatchBrowserEvent('open-create-department-modal');
    }

    public function save()
    {
        $this->validate();
        $this->department->save();

        $this->emit('departmentsUpdate');
        $this->dispatchBrowserEvent('close-create-department-modal');
        $this->isEditing
            ? $this->toast('Department Updated', "Department has been updated.")
            : $this->toast('Department Created', "Department has been created.");
    }

    public function render()
    {
        return view('livewire.accounts.departments.form');
    }
}
