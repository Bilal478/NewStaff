<?php

namespace App\Http\Livewire\Accounts\Members;

use App\Models\User;
use App\Models\Team;
use App\Models\Account;
use Livewire\Component;
use App\Rules\AtLeastOneOwner;
use Illuminate\Validation\Rule;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Department;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class MembersEditModal extends Component
{
    use Notifications;

    public $role = '';
    public $userId = null;
    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $permissions = [];
    public $team = '';
    public $department = '';
    public $team_id = [];
    public $department_id = [];
    public $punchin_pin_code='';
    public $is_owner;
    public $show_permission;

    protected $listeners = [
        'memberEdit' => 'edit',
    ];


    public function edit(User $user)
    {     
        $this->userId = $user->id;
        $check_owner=Subscription::where('user_id',$this->userId)->first();
        
        if($check_owner){
            $this->is_owner=true;
        }
        else{
            $this->is_owner=false;
        }
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->email = $user->email;
        $this->role = $this->currentRole;
        $this->handlePermissions();
        $this->punchin_pin_code = $user->punchin_pin_code;
        // dd(auth()->user());
        // $this->team_id = $user->teams;
        // $this->department_id = $user->departments;
        // dd($user->teams->toArray());
        // dd($user->teams->pivot->team_id);
        foreach ($user->teams as $key => $value) {
           $this->team_id[] =  $value->pivot->team_id;
        }
        foreach ($user->departments as $key => $value) {
           $this->department_id[] =  $value->pivot->department_id;
        }
        if($user->permissions!=NULL){
            $permissions=explode(',',$user->permissions);
            foreach ($permissions as $value) {
                $this->permissions[] =  $value;
             }
        }
        else{
            $this->permissions=[];
        }
        
        $this->dispatchBrowserEvent('open-member-edit-modal');
    }

    public function handlePermissions(){
        
        if($this->role != 'owner'){
            $this->show_permission = true;
        }
        else{
            $this->show_permission = false;
        }
    }

    public function save()
    {
        // $validatedDate = $this->validate([
        //     'role' => ['required', 'string', Rule::in(['owner', 'manager', 'member']), new AtLeastOneOwner($this->currentRole)],
        //     'team_id' => ['required', 'array'],
        //     'department_id' => ['required', 'array'],
        // ]);
        $this->account
            ->users()
            ->syncWithoutDetaching([$this->userId => ['role' => $this->role]]);

        $this->emit('membersUpdate');

        //get the company id
        $account= DB::table('account_user')
                ->where('user_id','=',$this->userId)
                ->get()->first();
        $account_id=$account->account_id;
        
        //Validate if punching code is used by other user in the company
        $data = DB::table('users')
                ->join('account_user', 'account_user.user_id', '=', 'users.id')
                ->where('users.punchin_pin_code', $this->punchin_pin_code)
                ->where('account_user.account_id', $account_id . '')
                ->where('users.id','<>', $this->userId)
                ->get();                

       if(count($data)>0)
       {
        $this->toast('Error', "Punch in pin code is being used.",'error',4000);
       }else{
        $user = User::find($this->userId);
        $user->punchin_pin_code = $this->punchin_pin_code;
        $string_of_permissions=implode(',',$this->permissions);
        $user->permissions=$string_of_permissions;
        $user->save();
        $user->teams()->sync($this->team_id);
        $user->departments()->sync($this->department_id);
        // $team = Team::find($validatedDate['team_id']);
        // $team->users()->sync($this->userId);
        // $department = Department::find($validatedDate['department_id']);
        // $department->users()->sync($this->userId);
        $this->dispatchBrowserEvent('close-member-edit-modal');
        $this->toast('Member Updated', "Member has been updated.");
       }


    }

    public function getCurrentRoleProperty()
    {
        return $this->account
            ->usersWithRole()
            ->where('users.id', $this->userId)
            ->first(['role'])->role;
    }

    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }

    public function render()
    {
        return view('livewire.accounts.members.edit-modal');
    }
}
