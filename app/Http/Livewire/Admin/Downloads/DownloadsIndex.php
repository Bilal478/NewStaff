<?php

namespace App\Http\Livewire\Admin\Downloads;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdminInvitation;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class DownloadsIndex extends Component
{
    use WithPagination, Notifications, WithFileUploads;

    public $search = '';
    public $filter = 'members';
    public $settingValues = [];
    public $labels = [];
    public $versionData ;
    public $windowSetupName = '';
    public $macSetupName = '';


    protected $listeners = [
        'refreshSettings' => 'refreshSettings',
    ];

    

    public function render()
    {

        return view('livewire.admin.downloads.index', [
        ])->layout('layouts.admin', ['title' => 'Settings']);
    }

    public function refreshSettings(){
         return redirect()->route('admin.settings');
    }
    public function mount()
    {
        $this->versionData =  DB::table('setup_version')->get();

         $this->windowSetupName = $this->versionData[0]->version_name;
         $this->macSetupName = $this->versionData[1]->version_name;
        
    }

    public function updateWindowSetup(){
        
       
            $this->validate([
                'windowSetupName' => ['required', 'string', 'max:200'],
            ]);

            $fileName = $this->windowSetupName.'.exe';
            DB::table('setup_version')->where('id',1)->update(['version_name'=>$fileName]);
            return  $this->toast('Window version updated.');
       
    }

    public function updateMacSetup(){
        
            
        $this->validate([
            'macSetupName' => ['required', 'string', 'max:200'],
        ]);

        $fileName = $this->macSetupName.'.dmg';
        DB::table('setup_version')->where('id',2)->update(['version_name'=>$fileName]);
        return  $this->toast('MAC version updated.');
    
   
}

}