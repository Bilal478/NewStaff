<?php

namespace App\Http\Livewire\Accounts\Activities;

use App\Models\Activity;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteImageActivityModal extends Component
{
    public $activity_id;
    protected $listeners = [
        'deleteImageActivityShow' => 'show',
        'deleteImageActivity' => 'deleteImageActivity',
    ];

    public function show($activity_id)
    {
        $this->activity_id = $activity_id;
        $this->openModal();
    }

    public function deleteImageActivity()
    {
        $screenshot = Screenshot::where('activity_id', $this->activity_id)->first();   

        if ($screenshot) {
            if (str_contains($screenshot->path, '1234567890.png') == false) {
                Storage::disk(Screenshot::STORAGE_DISK)->delete($screenshot->path);
                $screenshot->delete();
            }
     
        }
        $this->closeModal();
        return redirect('/activities');
    }

    public function render()
    {
        return view('livewire.accounts.activities.delete-image-activity-modal');
    }

    public function openModal()
    {
        $this->dispatchBrowserEvent('open-delete-image-activity-modal');
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-delete-image-activity-modal');
    }
}
