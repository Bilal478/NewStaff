<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
class ManagerSection extends Component
{
    use WithPagination;

    public $currentPage = 1;

    public function render()
    {
            $userId=Auth::user()->id;
            $authUserName=Auth::user()->firstname.' '.Auth::user()->lastname;
            $userDepartment=DB::table('department_admin')->where('user_id',$userId)->get();
              $departmentIds=[];
              foreach($userDepartment as $department){
                  $departmentIds[]=$department->department_id;
              }
              $uniqueDepartmentIds = array_unique($departmentIds);
              $userIds=[];
              foreach($uniqueDepartmentIds as $id){
              $usersOfDepartment=DB::table('department_user')->where('department_id',$id)->get();
              foreach($usersOfDepartment as $user){
                  $userIds[]=$user->user_id;
              }
          }
          $uniqueUserIds = array_unique($userIds);
          $userRecords = DB::table('users')
          ->whereIn('id', $uniqueUserIds)
          ->paginate(5);
        return view('livewire.manager-section', compact('userRecords', 'authUserName'));
    }
}
