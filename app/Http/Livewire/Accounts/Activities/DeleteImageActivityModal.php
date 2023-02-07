<?php

namespace App\Http\Livewire\Accounts\Activities;

use App\Models\Activity;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteImageActivityModal extends Component
{
    public $activity_id;
    public $activity1 = '';
    public $activity2 = '';
    public $firstScreenshotSelected = 1;
    public $secondScreenshotSelected = 1;
    public $numberOfScreenshots;
    protected $listeners = [
        'deleteImageActivityShow' => 'show',
        'deleteImageActivity' => 'deleteImageActivity',
    ];

    public function screenshotSelect1(){
        ($this->firstScreenshotSelected == 1)?($this->firstScreenshotSelected = 0):($this->firstScreenshotSelected = 1);
    }
    public function screenshotSelect2(){
        ($this->secondScreenshotSelected == 1)?($this->secondScreenshotSelected = 0):($this->secondScreenshotSelected = 1);
    }
    public function show($activity_id,$activity)
    {
        $this->activity_id = $activity_id;
        $this->activity1 = (url("/screenshots/{$activity[0]['path']}"));
        if(isset($activity[1])){
            $this->activity2 = (url("/screenshots/{$activity[1]['path']}"));
            $this->numberOfScreenshots = 2;
        }else{
            $this->activity2 = '';
            $this->numberOfScreenshots = 1;
        }
       
        $this->openModal();
    }

    public function deleteImageActivity()
    {
        $screenshot = Screenshot::where('activity_id', $this->activity_id)->get();
        
        if ($screenshot) {
            if($this->firstScreenshotSelected == 1){
                if (str_contains($screenshot[0]->path, '1234567890.png') == false) {
                    Storage::disk(Screenshot::STORAGE_DISK)->delete($screenshot[0]->path);
                    $screenshot[0]->delete();
                }
            }
            if((count($screenshot) == 2) && ($this->secondScreenshotSelected == 1)){
                if (str_contains($screenshot[1]->path, '1234567890.png') == false) {
                    Storage::disk(Screenshot::STORAGE_DISK)->delete($screenshot[1]->path);
                    $screenshot[1]->delete();
                }
            }
            
     
        }
        $this->closeModal();
        return redirect('/activities');
    }

    public function render()
    {
        return view('livewire.accounts.activities.delete-image-activity-modal',['activity_id'=>$this->activity_id]);
    }

    public function openModal()
    {
        $this->firstScreenshotSelected = 1;
        $this->secondScreenshotSelected = 1;
        $this->dispatchBrowserEvent('open-delete-image-activity-modal');
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('close-delete-image-activity-modal');
    }
}
