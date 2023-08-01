<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ActivitesModal extends Component
{

    public $date;
    public $user_id;
    public $activities=[];
    public $userName;
    public $account_id;
    public $project_id;
    public $task_id;
    public $activityToRemoved;

    protected $listeners = [
        'activityUpdate' => '$refresh',
        'activityDelete' => '$refresh',
        'activityModal' => 'show',
        'deleteConfirmedActivites' => 'deleteActivitySelected',
    ];


    public function deleteActivity($id)
    {
        Activity::find($id)->delete();
        $this->dispatchBrowserEvent('close-activity-modal');
    }

    public function show($date,$user_id,$account_id,$project_id,$task_id)
    {
        $this->date = $date;
        $this->user_id = $user_id;
        $this->account_id = $account_id;
        $this->project_id = $project_id;
        $this->task_id = $task_id;
        $this->activities = $this->selectedDateActivity();
        // $this->activities = Activity::where('date',date('Y-m-d',
        // strtotime($date)))->where('user_id', $user_id)->get();
        $this->showFormModal();
    }

    public function selectedDateActivity()
    {
        $startDate = Carbon::createFromFormat('M d, Y', $this->date);
        $startDate = $startDate->format('Y-m-d');
        
        if(!$this->user_id){
            $this->user_id = Auth::user()->id;
        }
        $name = User::where('id', $this->user_id)->first();
        $this->userName = $name->firstname.' '.$name->lastname;
        $results = Activity::join('users', 'activities.user_id', '=', 'users.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->where('activities.date', $startDate)
        ->where('activities.user_id', $this->user_id)
        ->where('activities.account_id', $this->account_id)
        ->where('activities.project_id', $this->project_id)
        ->where(function ($query) {
            $query->where('activities.task_id', $this->task_id)
                  ->orWhereNull('activities.task_id');
        })
        ->groupBy('activities.id', 'activities.user_id', 'activities.account_id', 'activities.date', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title', 'activities.seconds', 'activities.total_activity_percentage', 'activities.start_datetime', 'activities.end_datetime')
        ->selectRaw('activities.id, activities.user_id, activities.account_id, activities.task_id, activities.project_id, projects.title AS project_title, SUM(activities.seconds) AS seconds, activities.date, AVG(activities.total_activity_percentage) AS productivity, activities.start_datetime, activities.end_datetime,
            COALESCE(tasks.title, "No to-do") AS task_title')
        ->orderBy('activities.start_datetime')
        ->get();
        $ss = [];
        $arrayData = [];
        $seconds_sum_of_day = 0;
        $seconds_sum = 0;
        $start_time_index=0;
        $i=0;
        $j=0;
        foreach($results as $index=>$result){
            
            $startDateTime = Carbon::parse($result->end_datetime);
            $seconds_sum_of_day += $result->seconds;
            if(isset($results[$index+1])){
                if($seconds_sum == 0){
                    $start_time_index = $index;
                }
                $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
                
                $seconds_sum += $result->seconds;
                $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
                $ss[]=$diffInSeconds;
                if($diffInSeconds > 0 ){
                   $j++;
                    $arrayData[] = [
                        'user_id' => $result->user_id,
                        'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                        'end_time' => $result->end_datetime->format('h:i A'),
                        'date' => $result->date->format('Y-m-d'),
                        'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                        'minutes'=> $seconds_sum/60,
                        'productivity' => intval($result->productivity),
                        'project_id' => $result->project_id,
                        'project_title' => $result->project_title,
                        'task_id' => $result->task_id,
                        'account_id' => $result->account_id,
                        'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',
                        
                    ];
                    
                    
                    $seconds_sum = 0;
                    
                }
            }

            
        }

        // / Code to handle the last index
        $lastIndex = count($results) - 1;
        if ($seconds_sum > 0 && isset($results[$lastIndex])) {
            $lastResult = $results[$lastIndex];
            $arrayData[] = [
                'user_id' => $lastResult->user_id,
                'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                'end_time' => $lastResult->end_datetime->format('h:i A'),
                'date' => $lastResult->date->format('Y-m-d'),
                'duration' => CarbonInterval::seconds($seconds_sum+600)->cascade()->format('%H:%I:%S'),
                'minutes' => $seconds_sum / 60,
                'productivity' => intval($lastResult->productivity),
                'project_id' => $lastResult->project_id,
                'project_title' => $lastResult->project_title,
                'task_id' => $lastResult->task_id,
                'account_id' => $lastResult->account_id,
                'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
            ];
    }
     
    
        return $arrayData;
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-activity-modal');
    }
    public function render()
    {
        return view('livewire.activites-modal');
    }

    public function confirmDeleteActivity($data) {
        
        $this->activityToRemoved = $data;
        
        $this->dispatchBrowserEvent('show-delete-confirmation');
    

    }

    public function deleteActivitySelected(){
        
        $startDateTime = strtotime($this->activityToRemoved['date'] . ' ' . $this->activityToRemoved['start_time']);
        $endDateTime =strtotime($this->activityToRemoved['date'] . ' ' . $this->activityToRemoved['end_time']);

        $startDateTimeTemp =$this->activityToRemoved['date'].' '.date('H:i:s', strtotime($this->activityToRemoved['start_time']));
        $endDateTimeValueTemp =$this->activityToRemoved['date'].' '.date('H:i:s', strtotime($this->activityToRemoved['start_time']));

        $time=($endDateTime-$startDateTime)/600;
        
        for($i=0 ; $i<$time ; $i++){
            $temp = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($startDateTimeTemp,0,19) ) ) ;
			$new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime ( '+'.$i.'0 minutes ' , strtotime (substr($endDateTimeValueTemp,0,19)) ) ;
			$new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime ( '+10 minutes ' , strtotime ($new_end_time) ) ;
			$end_time = date('Y-m-d H:i:s', $temp_two_final);
            
            DB::table('activities')
            ->where('start_datetime', $new_start_time)
            ->where('end_datetime', $end_time)
            ->where('date', $this->activityToRemoved['date'])
            ->where('task_id', $this->activityToRemoved['task_id'])
            ->where('user_id', $this->activityToRemoved['user_id'])
            ->where('project_id', $this->activityToRemoved['project_id'])
            ->where('account_id', $this->activityToRemoved['account_id'])
            ->delete();    
        }
        $this->dispatchBrowserEvent('close-activity-modal');
        $this->emit('activityUpdate');
    
    }
}
