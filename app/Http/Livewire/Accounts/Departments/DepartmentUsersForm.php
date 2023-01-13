<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Models\User;
use App\Models\Department;
use Livewire\Component;

class DepartmentUsersForm extends Component
{
    public $departmentId;
    public $userId = '';

    public function getDepartmentProperty()
    {
        return Department::find($this->departmentId);
    }

    public function add()
    {
        $this->department->addMemeber($this->userId);
        $this->reset(['userId']);
    }

    public function remove($userId)
    {
        $this->department->removeMemeber($userId);

        $this->emit('tasksUpdate');
        $this->reset(['userId']);
    }

    public function render()
    {
        return view('livewire.accounts.departments.users-form', [
            'usersIn' => User::inDepartment($this->departmentId)->orderBy('firstname')->get(),
            'usersOut' => User::notInDepartment($this->departmentId)->orderBy('firstname')->get(),
        ]);
    }
}
