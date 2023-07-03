<?php

namespace App\Http\Livewire\Accounts\Activities;

use Livewire\Component;
use SebastianBergmann\Environment\Console;

class EditTimeModal extends Component
{
    public $userData;
    public $userData1;
    public $userData2;
    public $userData3;
    public $userData4;
    protected $listeners = [
    'showEditTimeModal' => 'EditTimeModal',
    ];
    
    public function render()
    {
        return view('livewire.accounts.activities.edit-time-modal');
    }
    public function EditTimeModal()
    {
        // $this->userData=$data;
        // $this->userData1=$data1;
        // $this->userData2=$data2;
        // $this->userData3=$data3;
        // $this->userData4=$data4;
        // dd($this->userData);

        $this->dispatchBrowserEvent('open-activities-edit-time-modal');

    }
  
    
}
