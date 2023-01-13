<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Carbon\Carbon;
use Livewire\Component;

class TrackModal extends Component
{

    public $start_time;
    public $end_time;
    public $date;
    public $activityId;
    public $message;

    protected $listeners = [
        'trackEdit' => 'show',
    ];


    public function save()
    {
        $activity = Activity::find($this->activityId);
        if (preg_match("/^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/", $this->start_time) && preg_match("/^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/", $this->end_time)) {
            $start =  Carbon::parse($this->date)->format('Y-m-d').' '.$this->start_time;
            $end =  Carbon::parse($this->date)->format('Y-m-d').' '.$this->end_time;
            $activity->update([
                'start_datetime' => $start,
                'end_datetime' => $end,
            ]);
            $this->message = null;
            $this->emit('activityUpdate');
            $this->dispatchBrowserEvent('close-track-modal');
        }else{
            $this->message = 'Please enter a valid time value';
            $this->start_time = $activity->start_datetime->format("H:i:s");
            $this->end_time = $activity->end_datetime->format("H:i:s");
        }
    }

    public function show($id,$date,$start,$end)
    {
        $this->message = null;
        $this->start_time = $start;
        $this->end_time = $end;
        $this->date = $date;
        $this->activityId = $id;
        $this->showFormModal();
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-track-modal');
    }
    public function render()
    {
        return view('livewire.track-modal');
    }
}
