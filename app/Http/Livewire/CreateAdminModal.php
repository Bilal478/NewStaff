<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Traits\Notifications;


class CreateAdminModal extends Component
{
    use Notifications;
    public $departmentId;
    public $userId = '';
    protected $listeners = [
        'createAdmin' => 'create',
        'showCreateAdmin' => 'show',
    ];
    
    public function create()
    {
       $user['user_id'] = $this->userId;
       $user['department_id'] = $this->departmentId;
       $department_admin=DB::table('department_admin')->insert($user);
       if($department_admin){
       $this->dispatchBrowserEvent('close-create-admin-modal');
        $this->toast('Admin Assigned', "Admin has been assigned.");
       }
    }
    public function show($id)
    {
        $this->departmentId=$id;
        $this->dispatchBrowserEvent('open-create-admin-modal');
    }
    public function render()
    {
        $currenturl = url()->current();
        $segments = explode('/', $currenturl);
        $lastSegment = end($segments);
        return view('livewire.create-admin-modal',[
            'usersIn' => User::inDepartment($lastSegment)->orderBy('firstname')->get()
        ]);
    }
  
}
