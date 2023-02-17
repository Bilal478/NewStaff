<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Mail\AdminInvite;
use App\Models\AdminInvitation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Traits\Notifications;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SettingsCreate extends Component
{
    use Notifications;

    public $email = '';
    public $firstname = '';
    public $lastname = '';
    public $adminId = '';

    public $settingName ;
    public $settingValue ;
   
    protected $listeners = [
        'addSetting' => 'show',
    ];
    
    
    public function show()
    {
        $this->reset();

        $this->dispatchBrowserEvent('open-create-modal');
    }

   

    public function create()
    {
        $this->validate([
            'settingName' => ['required', 'string', 'max:100'],
            'settingValue' => ['required', 'string', 'max:100'],
        ]);
        
       
            DB::table('neostaff_settings')->insert([
                'settings' => $this->settingName,
                'settings_value' => $this->settingValue,
            ]);
            
    
        
        $this->dispatchBrowserEvent('close-create-modal');
        $this->toast('New Setting Created.');
        $this->reset();
        $this->emit('refreshSettings');
    }

    public function render()
    {
        return view('livewire.admin.settings.create');
    }
}
