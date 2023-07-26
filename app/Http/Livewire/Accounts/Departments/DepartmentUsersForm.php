<?php

namespace App\Http\Livewire\Accounts\Departments;

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;

class DepartmentUsersForm extends Component
{
    use Notifications;
    public $departmentId;
    public $userId = '';

    protected $listeners = [
        'refreshDepartments' => '$refresh',
    ];

    public function getDepartmentProperty()
    {
        return Department::find($this->departmentId);
    }

    public function add()
    {
        $this->department->addMemeber($this->userId);
        $this->reset(['userId']);
        return redirect()->to('/departments/'.$this->departmentId);
        
    }
    public function removeAdminRoleOfDepartment($user_id,$department_id){
       
        $isAdminOfDepartment = DB::table('department_admin')
        ->where('user_id', $user_id)
        ->where('department_id', $department_id)
        ->delete();
        if($isAdminOfDepartment){
            $this->toast('Admn Role', "Admin Role Removed.");
        }
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
            'departmentId' => $this->departmentId,
        ]);
    }
}
