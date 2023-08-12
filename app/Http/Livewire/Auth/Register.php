<?php

namespace App\Http\Livewire\Auth;

use App\Models\Account;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $accountName = '';
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $accountFound = false;
    public $account ;
    public $user;

    public function register()
    {
        if($this->accountFound){
    
            $this->validate([
                'accountName' => ['required', 'max:100'],
                'firstName' => ['required', 'max:50'],
                'lastName' => ['required', 'max:50'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8'],
            ]);

            $currentAccount = Account::where('id',$this->account->id)->first();
            $currentAccount->name = $this->accountName;
            $currentAccount->update();

            $currentUser = User::where('id',$this->user->id)->first();
            $currentUser->firstname = $this->firstName;
            $currentUser->lastname = $this->lastName;
            $currentUser->email = $this->email;
            $currentUser->password = Hash::make($this->password);
            $currentUser->update();
            
       
            Auth::login($currentUser, true);
            
            return redirect()->intended('billing_information?plan=Monthly');
        }
        else{
            
            $this->validate([
                'accountName' => ['required', 'max:100'],
                'firstName' => ['required', 'max:50'],
                'lastName' => ['required', 'max:50'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:8'],
                // 'g-recaptcha-response' => ['required'],
            ]);
    
            $account = Account::create([
                'name' => $this->accountName,
            ]);
            
    
            $user = User::create([
                'firstname' => $this->firstName,
                'lastname' => $this->lastName,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);


            $department = Department::create([
                'title' => $account->name.' Department',
                'account_id' => $account->id,
            ]);

            $project = Project::create([
                'title' => $account->name.' Project',
                'description' => $account->name.' Project Description',
                'account_id' => $account->id,
                'department_id' => $department->id,
            ]);

            Task::create([
                'title' => $account->name.' Task',
                'description' => $account->name.' Task Description',
                'project_id' => $project->id,
                'account_id' => $account->id,
                'user_id' => $user->id,
                'department_id' => $department->id,
            ]);

            DB::table('project_user')->insert([
                'project_id' => $project->id,
                'user_id' => $user->id,
            ]);

            DB::table('account_user')->insert([
                'role' => 'owner',
                'account_id' => $account->id,
                'user_id' => $user->id,
                'allow_edit_time' => 1,
                'allow_delete_screenshot' => 1,
            ]);
            
            // $account->addOwner($user);
    
            session(['account' => $account->id]);
            
            Auth::login($user, true);
            
            return redirect()->intended('billing_information?plan=Monthly');
        }
       

        //return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth', ['title' => 'Sign up']);
    }

    public function mount()
    {
        $account_user = DB::table('account_user')->where('account_id', session()->get('account'))->first();
    
        if( $account_user){
            $this->account = Account::where('id',session()->get('account'))->first();

            $this->user = User::where('id',$account_user->user_id)->first();
            $this->accountName = $this->account->name;
            $this->firstName = $this->user->firstname;
            $this->lastName = $this->user->lastname;
            $this->email = $this->user->email;
            $this->accountFound = true;
        }
    }
}
