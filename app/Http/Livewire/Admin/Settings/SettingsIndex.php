<?php

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdminInvitation;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsIndex extends Component
{
    use WithPagination, Notifications;

    public $search = '';
    public $filter = 'members';
    public $settingValues = [];
    public $labels = [];
    public $settingsData ;
    public $emails ;


    protected $listeners = [
        'refreshSettings' => 'refreshSettings',
    ];

    

    public function render()
    {
        return view('livewire.admin.settings.index', [
        ])->layout('layouts.admin', ['title' => 'Settings']);
    }

    public function refreshSettings(){
         return redirect()->route('admin.settings');
    }
    public function mount()
    {
        $this->settingsData =  DB::table('neostaff_settings')->get();
        $emailsRecord =  DB::table('registration_email_receivers')->first();
        $this->emails = $emailsRecord->email;
       
        foreach($this->settingsData as $index=>$setting){
            $this->labels[$index] = $setting->settings;
        }
        
    }


    public function save(){
        foreach($this->settingsData as $index=>$setting){
            DB::table('neostaff_settings')->where('ID',$setting['ID'])->update(['settings_value'=>$setting['settings_value']]);
        }
        $this->toast('Settings Updated.');
    }
    public function saveEmail(){
        $this->validate([
            'emails' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $emails = explode(';', $value);
                    if (count($emails) !== count(array_unique($emails))) {
                        $fail("The $attribute field contains duplicate email addresses.");
                    }
                    foreach ($emails as $email) {
                        if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            $fail("The $attribute field contains an invalid email address.");
                        }
                    }
                }
            ],
        ]);
        $emails = explode(';', $this->emails);
        $uniqueEmails = array_unique($emails); // Remove duplicates
    
        // Save the emails
        $emailString = implode(';', $uniqueEmails);
    
        $emailsRecord = DB::table('registration_email_receivers')->first();
    
        if (!$emailsRecord) {
            DB::table('registration_email_receivers')->insert([
                'email' => $emailString,
            ]);
            $this->toast('Registration email is inserted.');
        } else {
            DB::table('registration_email_receivers')->where('id', 1)->update(['email' => $emailString]);
            $this->toast('Registration email is updated.');
        }
    }
    
   
}
