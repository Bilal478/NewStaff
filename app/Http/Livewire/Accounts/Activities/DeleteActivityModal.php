<?php

namespace App\Http\Livewire\Accounts\Activities;

use App\Models\Activity;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteActivityModal extends Component
{
    public $activity_id;
    protected $listeners = [
        'deleteActivityShow' => 'show',
        'deleteActivity' => 'deleteActivity',
    ];

    public function show($activity_id)
    {
        $this->activity_id = $activity_id;
        $this->openModal();
    }

    public function deleteActivity()
    {
        $activity = Activity::find($this->activity_id);
        $screenshot = Screenshot::where('activity_id', $this->activity_id)->first();
        
        if ($screenshot) {
            if (str_contains($screenshot->path, '1234567890.png') == false) {
                Storage::disk(Screenshot::STORAGE_DISK)->delete($screenshot->path);
            }
            $screenshot->delete();
        }
        if ($activity) {
            $activity->delete();  
        }
        $this->closeModal();
        return redirect('/activities');
    }

    public function render()
    {
        return view('livewire.accounts.activities.delete-activity-modal');
    }

    public function openModal()
    {
        $this->dispatchBrowserEvent('open-delete-activity-modal');
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-delete-activity-modal');
    }
}
