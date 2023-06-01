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
    public $userId = [];
    public $user_id = [];
    public $department_id = [];
    public $manager_id = [];
    protected $listeners = [
        'createAdmin' => 'create',
        'showCreateAdmin' => 'show',
    ];
    
    public function create()
    {
       
       $user['user_id'] = $this->manager_id;
       $user['department_id'] = $this->departmentId;
       $currentDepartment = Department::where('id',$this->departmentId)->first();
       if(count($user['user_id']) > 0){
            foreach($user['user_id'] as $id){
                $isDepartmentAssigned = DB::table('department_user')->where(['user_id'=>$id,'department_id'=>$this->departmentId])->first();
                
               if(!$isDepartmentAssigned){
                    $currentDepartment->addMemeber($id);
                }
                $department_admin=DB::table('department_admin')->insert(['user_id'=>$id,'department_id'=>$this->departmentId]);
            }
       }
   
       if($department_admin){
        $this->dispatchBrowserEvent('close-create-admin-modal');
        $this->toast('Admin Assigned', "Admin has been assigned.");
        $this->emit('refreshDepartments');
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
        
        $account = DB::table('account_user')->where('user_id',auth()->user()->id)->first();
        $userIds = DB::table('account_user')->where(['account_id'=>$account->account_id,'role'=>'manager'])->pluck('user_id')->toArray();
        $manager = User::whereIn('id', $userIds)->orderBy('firstname')->get();

        return view('livewire.create-admin-modal',[
            'usersIn' => $manager
        ]);
    }
  
}
