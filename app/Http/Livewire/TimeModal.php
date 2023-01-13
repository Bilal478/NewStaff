<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Livewire\Component;

class TimeModal extends Component
{

    public $seconds;
    public $activityId;
    public $name;
    public $message;
    public $title;

    protected $listeners = [
        'timeEdit' => 'show',
    ];

    public function save()
    {
        $activity = Activity::find($this->activityId);
        if (preg_match("/^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/", $this->seconds)) {
            $parsed = date_parse($this->seconds);
            $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

            $activity->update([
                'seconds' => $seconds,
            ]);
            $this->message = null;
            $this->emit('activityUpdate');
            $this->dispatchBrowserEvent('close-time-modal');
        } else {
            $this->message = 'Please enter a valid time value';
            $this->seconds = gmdate("H:i:s", $activity->seconds);
        }

    }

    public function show($id, $seconds,$title=null)
    {
        $this->message = null;
        $this->title = $title;
        $this->activityId = $id;
        $this->name = 'Activity '.$id;
        $this->seconds = gmdate("H:i:s", $seconds);
        $this->showFormModal();
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-time-modal');
    }

    public function render()
    {
        return view('livewire.time-modal');
    }
}
