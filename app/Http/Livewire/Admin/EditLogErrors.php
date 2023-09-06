<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Http\Livewire\Traits\Notifications;

class EditLogErrors extends Component
{
    use Notifications;
    public $logErrorId;
    public $error_message;
    public $comments;
    public $status;
    protected $listeners = [
        'logErrorsEdit' => 'edit',
    ];
    
    public function render()
    {
        return view('livewire.admin.edit-log-errors');
    }
    public function edit($logErrorId){
        $this->logErrorId=$logErrorId;
        $logRecord = DB::table('logs_data')->where('id', $this->logErrorId)->first();
        $this->error_message=$logRecord->message;
        $this->status=$logRecord->status;
        $this->dispatchBrowserEvent('open-edit-log-errors');
    }
    public function save()
{
    $errorData = DB::table('logs_data')->where('id', $this->logErrorId)->first();
    if ($errorData) {
        $updateData = [
            'status' => $this->status,
        ];

        if (!empty($this->comments)) {
            $updateData['comments'] = $this->comments;
        }

        DB::table('logs_data')
            ->where('id', $this->logErrorId)
            ->update($updateData);

        $this->closeModal();
        $this->emit('logErrorUpdate');
        $this->toast('Data Updated', "The log errors data has been updated.");
    }
}

    public function closeModal()
    {
   
    $this->dispatchBrowserEvent('close-edit-log-errors');
    $this->comments=NULL;
    }
   
}
