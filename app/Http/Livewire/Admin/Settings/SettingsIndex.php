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
   
}
