<?php

namespace App\Http\Livewire\Accounts\Activities;

use App\Http\Livewire\Traits\Notifications;
use App\Models\User;
use Livewire\Component;
use SebastianBergmann\Environment\Console;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EditTimeModal extends Component
{
    use Notifications;
    public $userData=[];
    public $userId;
    public $accountId;
    public $taskId;
    public $projectId;
    public $firstName;
    public $lastName;
    public $projectTitle;
    public $taskTitle;
    public $duration;
    public $startTime;
    public $endTime;
    public $date;
    public $simpleDate;
    public $newStartTime;
    public $newEndTime;
    public $activityToRemoved = null;
    protected $listeners = [
        'showEditTimeModal' => 'EditTimeModal',
        'deleteConfirmed' => 'deleteActivitySelected',
    ];
    
    public function render()
    {
        return view('livewire.accounts.activities.edit-time-modal');
    }
    public function EditTimeModal($data)
    {
        
        if (!empty($data)) {
            $user=User::where('id','=',$data['user_id'])->get();
            $this->userId = $user[0]['id'];
            $this->firstName = $user[0]['firstname'];
            $this->lastName = $user[0]['lastname'];
            $this->projectTitle = $data['project_title'];
            $this->accountId = $data['account_id'];
            $this->taskId = $data['task_id'];
            $this->projectId = $data['project_id'];
            $this->taskTitle = $data['task_title'];
            $this->duration = $data['duration'];
            $this->startTime = $data['start_time'];
            $this->endTime = $data['end_time'];
            $this->date = Carbon::parse($data['date'])->format('D, M j, Y');
            $this->simpleDate = $data['date'];

        }
        $this->dispatchBrowserEvent('open-activities-edit-time-modal');

        

    }
    public function update(){

        if($this->newStartTime==''){
            $this->newStartTime=$this->startTime;
            // dd( $this->newStartTime,$this->newEndTime);
        }
        if($this->newEndTime==''){
            $this->newEndTime=$this->endTime;
            // dd( $this->newStartTime,$this->newEndTime);
        }
        $newStartTimeValue =$this->simpleDate.' '.date('H:i:s', strtotime($this->newStartTime));
        $newEndTimeValue = $this->simpleDate.' '.date('H:i:s', strtotime($this->newEndTime));
        $newStartTimeValueTemp =strtotime($this->simpleDate . ' ' . $this->newStartTime);
        $newEndTimeValueTemp = strtotime($this->simpleDate . ' ' . $this->newEndTime);
        $oldStartTimeValue =$this->simpleDate.' '.date('H:i:s', strtotime($this->startTime));
        $oldEndTimeValue = $this->simpleDate.' '.date('H:i:s', strtotime($this->endTime));
        $oldStartTimeValueTemp = strtotime($this->simpleDate . ' ' . $this->startTime);
        $oldEndTimeValueTemp = strtotime($this->simpleDate . ' ' . $this->endTime);
       
    if($newStartTimeValue<$oldStartTimeValue){
        $time=($oldStartTimeValueTemp-$newStartTimeValueTemp)/600;
            for($i=0 ; $i<$time ; $i++){
            $temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($newStartTimeValue,0,19) ) ) ;
            $new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($newStartTimeValue,0,19)) ) ;
            $new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
            $end_time = date('Y-m-d H:i:s', $temp_two_final);

            $data = DB::table('activities')
            ->where('start_datetime', '>=', $new_start_time)
            ->where('end_datetime', '<=', $end_time)
            ->where('user_id', '=', $this->userId)
            // ->where('project_id', '=', $this->projectId)
            ->where('account_id', '=', $this->accountId)
            // ->where('task_id', '=', $this->task->id)
            ->whereNull('deleted_at')
            ->get();

            //Count rows finded in the period of time
            $rows = count($data);
            // dd('a',$rows);
            if($rows>0)
            {
                $this->toast('Activity can not be created', "There is an activity in this period of time TimeStart: " . $new_start_time .
                " - End time: " . $end_time,'error',15000);
                return false;  
            }
            if($rows == 0){
            DB::table('activities')->insert([
                'from' => 621,
                'to' => 1200,
                'seconds' => 600, 
                'start_datetime' => $new_start_time,
                'date' => $this->simpleDate,
                'keyboard_count' => 100,
                'mouse_count' => 40,
                'total_activity' => 100,
                'total_activity_percentage' => 100,
                'task_id' => $this->taskId,
                'end_datetime' => $end_time, 
                'user_id' => $this->userId,
                'project_id' => $this->projectId,
                'account_id' => $this->accountId,
                'created_at' =>  $newStartTimeValue, 
                'updated_at' =>  $newStartTimeValue, 
            ]);
            }    
        }
    }
    if($newEndTimeValue>$oldEndTimeValue){
        $time=($newEndTimeValueTemp-$oldEndTimeValueTemp)/600;
        for($i=0 ; $i<$time ; $i++){
            $temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($oldEndTimeValue,0,19) ) ) ;
			$new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($oldEndTimeValue,0,19)) ) ;
			$new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
			$end_time = date('Y-m-d H:i:s', $temp_two_final);

            $data = DB::table('activities')
            ->where('start_datetime', '>=', $new_start_time)
            ->where('end_datetime', '<=', $end_time)
            ->where('user_id', '=', $this->userId)
            // ->where('project_id', '=', $this->projectId)
            ->where('account_id', '=', $this->accountId)
            // ->where('task_id', '=', $this->task->id)
            ->whereNull('deleted_at')
            ->get();

        //Count rows finded in the period of time
        $rows = count($data);
        // dd('b',$rows);
        if($rows>0)
        {
            $this->toast('Activity can not be created', "There is an activity in this period of time TimeStart: " . $new_start_time .
            " - End time: " . $end_time,'error',15000);
            return false;  
        }
        if($rows == 0){
            DB::table('activities')->insert([
                'from' => 621,
                'to' => 1200,
                'seconds' => 600, 
                'start_datetime' => $new_start_time, 
                'date' => $this->simpleDate,
                'keyboard_count' => 100,
                'mouse_count' => 40,
                'total_activity' => 100,
                'total_activity_percentage' => 100,
                'task_id' => $this->taskId,
                'end_datetime' => $end_time, 
                'user_id' => $this->userId,
                'project_id' => $this->projectId,
                'account_id' => $this->accountId,
                'created_at' =>  $newStartTimeValue, 
                'updated_at' =>  $newStartTimeValue,
            ]);
        }    
        }
    }
    if($newStartTimeValue>$oldStartTimeValue && $newStartTimeValue<$oldEndTimeValue ){
        $time=($newStartTimeValueTemp-$oldStartTimeValueTemp)/600;
        for($i=0 ; $i<$time ; $i++){
            $temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($oldStartTimeValue,0,19) ) ) ;
			$new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($oldStartTimeValue,0,19)) ) ;
			$new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
			$end_time = date('Y-m-d H:i:s', $temp_two_final);
            DB::table('activities')
            ->where('start_datetime', $new_start_time)
            ->where('end_datetime', $end_time)
            ->where('date', $this->simpleDate)
            ->where('task_id', $this->taskId)
            ->where('user_id', $this->userId)
            ->where('project_id', $this->projectId)
            ->where('account_id', $this->accountId)
            ->delete();    
        }
    }
    if($newEndTimeValue<$oldEndTimeValue && $newEndTimeValue>$oldStartTimeValue ){
        $time=($oldEndTimeValueTemp-$newEndTimeValueTemp)/600;
        for($i=0 ; $i<$time ; $i++){
            $temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($newEndTimeValue,0,19) ) ) ;
			$new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($newEndTimeValue,0,19)) ) ;
			$new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
			$end_time = date('Y-m-d H:i:s', $temp_two_final);
            DB::table('activities')
            ->where('start_datetime', $new_start_time)
            ->where('end_datetime', $end_time)
            ->where('date', $this->simpleDate)
            ->where('task_id', $this->taskId)
            ->where('user_id', $this->userId)
            ->where('project_id', $this->projectId)
            ->where('account_id', $this->accountId)
            ->delete();   
        }
    }
        $this->dispatchBrowserEvent('close-activity-modal');
        $this->dispatchBrowserEvent('close-task-show-modal');
        $this->dispatchBrowserEvent('close-activities-edit-time-modal');
        if(!($newStartTimeValue==$oldStartTimeValue && $newEndTimeValue==$oldEndTimeValue)){
            $this->emit('activityUpdate');
            $this->emit('tasksUpdate');
		$this->toast('Time Updated', "The Time has been updated successfully ");
        }
        $this->newStartTime = '';
        $this->newEndTime = '';
    
    }
  
    
}