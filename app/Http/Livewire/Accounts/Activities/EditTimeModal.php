<?php

namespace App\Http\Livewire\Accounts\Activities;

use App\Models\User;
use Livewire\Component;
use SebastianBergmann\Environment\Console;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EditTimeModal extends Component
{
    public $userData=[];
    public $firstName;
    public $lastName;
    public $projectTitle;
    public $taskTitle;
    public $duration;
    public $startTime;
    public $endTime;
    public $date;
    public $newStartTime;
    public $newEndTime;
    protected $listeners = [
    'showEditTimeModal' => 'EditTimeModal',
    ];
    
    public function render()
    {
        return view('livewire.accounts.activities.edit-time-modal');
    }
    public function EditTimeModal($data)
    {
        
        if (!empty($data)) {
            $user=User::where('id','=',$data['user_id'])->get();
            $this->firstName = $user[0]['firstname'];
            $this->lastName = $user[0]['lastname'];
            $this->projectTitle = $data['project_title'];
            $this->taskTitle = $data['task_title'];
            $this->duration = $data['duration'];
            $this->startTime = $data['start_time'];
            $this->endTime = $data['end_time'];
            $this->date = $this->date = Carbon::parse($data['date'])->format('D, M j, Y');

        }
        $this->dispatchBrowserEvent('open-activities-edit-time-modal');

        

    }
    public function update(){
        $newStartTimeValue = $this->newStartTime;
        $newEndTimeValue = $this->newEndTime;
        dd( $newStartTimeValue,$newEndTimeValue);
        // DB::table('activities')
        // ->where('id', $activityId) 
        // ->update([
        //     'from' => 621,
        //     'to' => 1200,
        //     'seconds' => $hour_in_seconds_two - $hour_in_seconds,
        //     'start_datetime' => $new_start_time, 
        //     'date' => $this->datetimerange,
        //     'keyboard_count' => 100,
        //     'mouse_count' => 40,
        //     'total_activity' => 100,
        //     'total_activity_percentage' => 100,
        //     'task_id' => $this->task_info->id,
        //     'end_datetime' => $end_time,
        //     'user_id' => $this->task_info->user_id,
        //     'project_id' => $this->task_info->project_id,
        //     'account_id' => $this->task_info->account_id,
        //     'updated_at' => $new_start_time, 
        // ]);
    
    }
  
    
}
