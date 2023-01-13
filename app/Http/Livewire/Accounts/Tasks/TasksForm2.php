<?php

namespace App\Http\Livewire\Accounts\Tasks;

use Illuminate\Support\Facades\DB;

use App\Http\Livewire\Traits\Notifications;
use App\Models\Activity;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

use DateTime;

class TasksForm2 extends Component
{
	use AuthorizesRequests, Notifications;
	
	public $taskId = null;
	
	public $seconds_one_task;
	public $seconds_two_task;
	public $datetimerange;
	
	public $task_info;
	
	 protected $listeners = [
        'activity' => 'Show',
    ];
	
	public function Show(Task $task){
		$this->task_info = $task;
		$this->dispatchBrowserEvent('open-activities-form-modal-2');
	}
	
	public function create_activity(){
		 
		$temp = substr($this->seconds_one_task, 0, 8);
		$temp_two = substr($this->seconds_two_task, 0, 8);
		
		list($hours, $minutes, $seconds) = explode(':', $temp);
		$hour_in_seconds = ($hours * 3600 ) + ($minutes * 60 ) + $seconds;
		
		list($hours_two, $minutes_two, $seconds_two) = explode(':', $temp_two);
		$hour_in_seconds_two = ($hours_two * 3600 ) + ($minutes_two * 60 ) + $seconds_two;
		
		$start_datetime = $this->datetimerange.' '.$this->seconds_one_task;
		$end_datetime = $this->datetimerange.' '.$this->seconds_two_task;
		$time =  ($hour_in_seconds_two - $hour_in_seconds) /600 ;
		
		for ($i = 0; $i < $time; $i++) {
			
			$temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($start_datetime,0,19) ) ) ;
			$new_start_time = date('Y-m-d H:i:s', $temp);
			$temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($start_datetime,0,19)) ) ;
			$new_end_time = date('Y-m-d H:i:s', $temp_two);
			$temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
			$end_time = date('Y-m-d H:i:s', $temp_two_final);
			
			
			DB::table('activities')->insert([
				'from' => 621,
				'to' => 1200, 
				'seconds' => $hour_in_seconds_two - $hour_in_seconds,
				'start_datetime' =>   $new_start_time, //substr($start_datetime,0,19),
				'date' => $this->datetimerange,
				'keyboard_count' => 100,
				'mouse_count'=>40,
				'total_activity'=>100, 
				'total_activity_percentage' => 100,
				'task_id' => $this->task_info->id,
				'end_datetime' => $end_time, //substr($end_datetime,0,19),
				'user_id' => $this->task_info->user_id,
				'project_id' => $this->task_info->project_id,
				'account_id' => $this->task_info->account_id,
				'created_at' =>  $new_start_time, //substr($start_datetime,0,19), 
				'updated_at' =>  $new_start_time, //substr($start_datetime,0,19)
			]);
			
			$temp = DB::table('activities')->select('id')
			->orderBy('id', 'DESC')
			->take(5)
			->get();
			
			$id_activity = $temp[0]->id;
			
			DB::table('screenshots')->insert([	
				'path' => '00/1234567890.png',
				'activity_id' => $id_activity,
				'account_id' => $this->task_info->account_id,
				'created_at' => $this->task_info->datetimerange,
				'updated_at' => $this->task_info->datetimerange
			]);
		}
		$this->dispatchBrowserEvent('close-activities-form-modal-2');
		$this->toast('Activity Created', "The Activity has been created from ".
		$this->seconds_one_task." to ".$this->seconds_two_task);
	 }
 
    public function render()
    {
	return view('livewire.accounts.tasks.form2')->with('task', $this->task_info);
		
    }
}