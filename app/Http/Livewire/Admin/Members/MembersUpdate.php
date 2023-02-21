<?php

namespace App\Http\Livewire\Admin\Members;

use Livewire\Component;
use App\Mail\AdminInvite;
use App\Models\AdminInvitation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class MembersUpdate extends Component
{
    use Notifications;

    public $email = '';
    public $firstname = '';
    public $lastname = '';
    public $adminId = '';
    public $password = '';
    public $password_confirmation = '';
   
    protected $listeners = [
        'updateAdmin' => 'show',
        'adminData'
    ];
    
    public function adminData($admin)
   {
    
    $this->adminId = $admin['id'];
     $this->email = $admin['email'];
     $this->firstname = $admin['firstname'];
     $this->lastname = $admin['lastname'];
   }
    public function show()
    {
        $this->reset();

        $this->dispatchBrowserEvent('open-update-modal');
    }

   

    public function create()
    {
        $this->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);
        
        $admin = Admin::where('id',$this->adminId)->first();
        $admin->firstname =  $this->firstname;
        $admin->lastname =  $this->lastname;
        $admin->email =  $this->email;

        if($this->password){
            $admin->password =  Hash::make($this->password);
        }
        
        $admin->update();

    
        
        $this->dispatchBrowserEvent('close-update-modal');
        $this->toast('Admin Updated.');
        $this->reset();
        $this->emit('adminInvitationSend');
    }

    public function render()
    {
        return view('livewire.admin.members.update');
    }
}
