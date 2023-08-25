<?php

namespace App\Http\Livewire\Accounts\Members;

use App\Models\Account;
use App\Models\Plans;
use Livewire\Component;
use App\Mail\AccountInvite;
use Illuminate\Validation\Rule;
use App\Rules\IsNotMemberOfAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Rules\AccountInvitationUnique;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Department;
use App\Models\Subscription;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class MembersInvite extends Component
{
    use Notifications;

    public $email = '';
    public $role = 'member';
    public $selectedDepartment = null;
    public $selectedProject = null;
    public $user_exist;


    protected $listeners = [
        'memberInvite' => 'show',
    ];

    public function show()
    {
        $this->reset(['email', 'role']);

        $this->dispatchBrowserEvent('open-invite-modal');		
    }
	
    public function create()
    {
        $validated = $this->validate([
            'role' => ['required', 'string', Rule::in(['owner', 'manager', 'member'])],
            'email' => ['required', 'email', 'max:100', new AccountInvitationUnique, new IsNotMemberOfAccount],
        ]); 

        $departmentId = $this->selectedDepartment;
        $projectId = $this->selectedProject;
        $randomID = substr(md5(uniqid(mt_rand(), true)), 0, 16);
        $prefix = 'sub_1';
        $length = 28 - strlen($prefix);
        $randomString = Str::random($length);
        $stripe_id = $prefix . $randomString;   
        $userExist = DB::table('users')
        ->where('email', $this->email)
        ->first();    
        if(!$userExist){
        $user = User::create([
            'firstname' => 'new',
            'lastname' => 'user',
            'email' => $this->email,
            'password' => Hash::make(12345678),
        ]);
    }
    else{
      DB::table('users')
            ->where('id', $userExist->id)
            ->update(['multiple_company' => 1]);
            $userExist = DB::table('users')
            ->where('email', $this->email)
            ->first();
        $user= $userExist;
    }
        DB::table('project_user')->insert([
            'project_id' => $projectId,
            'user_id' => $user->id,
        ]);
        DB::table('department_user')->insert([
            'department_id' => $departmentId,
            'user_id' => $user->id,
        ]);
        DB::table('subscriptions')->insert([
            'user_id' => $user->id,
            'name' => 'Annual',
            'stripe_id' =>  $stripe_id,
            'stripe_status' => 'active',
        ]);
        DB::table('account_user')->insert([
            'role' => $this->role,
            'account_id' => $this->account->id,
            'user_id' => $user->id,
            'allow_edit_time' => 1,
            'allow_delete_screenshot' => 1,
        ]);
        DB::table('verify_invitations')->insert([
            'user_id' => $user->id,
            'verification_id' => $randomID,
        ]);
        $validated['user_id'] = Auth::user()->id;

        $accountInvitation = $this->account->invitations()->create($validated);
    
    

        Mail::to($accountInvitation->email)
            ->send(new AccountInvite($this->account, $accountInvitation,$randomID,$this->user_exist,$userExist));

        $this->emit('memberInvitationSend');
        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to {$this->email} has been sent.");
        $this->reset(['email', 'role']);
    }

	 public function create2()
    {
     

        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to has been sent.");
       
    }

	
    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }

    public function render()
    {
        $account = Account::find(session()->get('account_id'));
        $departments=Department::where('account_id',$account['id'])->get();
        $projects = [];
        $tasks = [];
        if ($this->selectedDepartment) {
            $selectedDepartment = Department::find($this->selectedDepartment);
            if ($selectedDepartment) {
                $projects = $selectedDepartment->projects()->get();
            }
        }
		$data = [
            'intent' => auth()->user()->createSetupIntent(),
            'departments' => $departments,
            'projects' => $projects,
        ];
        return view('livewire.accounts.members.invite')->with($data);
    }
    
    public function updatedSelectedDepartment($value)
    {
        // Reset the selected project when the department changes
        $this->selectedProject = null;
    }
	
}
