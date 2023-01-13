<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use Livewire\Component;

class ActivitesModal extends Component
{

    public $date;
    public $user_id;
    public $activities=[];

    protected $listeners = [
        'activityUpdate' => '$refresh',
        'activityDelete' => '$refresh',
        'activityModal' => 'show',
    ];


    public function deleteActivity($id)
    {
        Activity::find($id)->delete();
        $this->dispatchBrowserEvent('close-activity-modal');
    }

    public function show($date,$user_id)
    {
        $this->date = $date;
        $this->user_id = $user_id;
        $this->activities = Activity::where('date',date('Y-m-d',
        strtotime($date)))->where('user_id', $user_id)->get();
        $this->showFormModal();
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-activity-modal');
    }
    public function render()
    {
        return view('livewire.activites-modal');
    }
}
