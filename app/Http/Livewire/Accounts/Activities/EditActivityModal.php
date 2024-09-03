<?php

namespace App\Http\Livewire\Accounts\Activities;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Task;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

class EditActivityModal extends Component
{
    public $date;
    use WithPagination;

    public $activityIDBeingRemoved = null;
    public $taskId = null;

    public $seconds_one;
	public $seconds_two;
	public $datetimerange;
    public $userName;
    public $user_id;
    public $activityToRemoved;
    private $list_activities=array();

    protected $listeners = [
        'editActivityShow' => 'show',
        'activityUpdate'=> '$refresh',
        'deleteActivity' => 'deleteActivity',
        'activityCreate' => 'create',
        'deleteConfirmed' => 'deleteActivitySelected',
        'refreshActivities' => '$refresh',
        'refreshPagination' => 'refreshPagination',
        'deleteConfirmedActivitesFromTask' => 'deleteActivitySelected',
    ];
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function deleteActivity($id)
    {   
        Activity::find($id)->delete();
    }

    public function refreshPagination()
    {
        $lastpage = Activity::where('task_id',$this->taskId)
        ->whereDate('date', $this->formatted_date)
        ->paginate(10)->lastPage();

        if($lastpage>0)
        {
            $this->setPage($lastpage);
        }
    }


    public function addDay()
    {
        $this->date = Carbon::createFromFormat('M d, Y', $this->date)->addDay()->format('M d, Y');
    }
    public function subDay()
    {
        $this->date = Carbon::createFromFormat('M d, Y', $this->date)->subDay()->format('M d, Y');
    }
    public function show($userId,$date){
        // dd($this->selectedDateActivity());
        $this->user_id = $userId;
        $this->date = $date;
        // dd($this->user_id, $this->date);
        $this->dispatchBrowserEvent('open-edit-activity-modal');
    }
    public function render()
    {
        if ($this->date) {
            Session::put('date', $this->date);
        } else {
            $this->date = Session::get('date', Carbon::today()->format('M d, Y'));
            Session::forget('date');
            Session::put('date', $this->date);
        }
        $this->list_activities= Activity::where('task_id',$this->taskId)
        ->whereDate('date', $this->formatted_date)
        ->paginate(10);
        $count = count($this->list_activities);
        return view('livewire.accounts.activities.edit-activity-modal',[
            'task' => $this->task, 'activitiesGroup' => $this->activities(),
            'activities' => $this->list_activities, 'count' => $count,
            'selectedDateRecord' => $this->selectedDateActivity()
        ]);
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
        $results = Activity::where('activities.date', $startDate)
        ->where('activities.user_id', $this->user_id)
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->select('activities.*', 
                 DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
                 DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
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
            // dd($result);
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
                'duration' => CarbonInterval::seconds($seconds_sum+$lastResult->seconds)->cascade()->format('%H:%I:%S'),
                'minutes' => ($seconds_sum+$lastResult->seconds / 60),
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
    public function activities()
    {
        return Auth::guard('web')->user()->isOwnerOrManager()
            ?  $this->activitiesForUser()
            : $this->activitiesForUser();
    }

    public function getTaskProperty()
    {
        return Task::with('user:id,firstname,lastname')
            ->find($this->taskId);
    }
  
    public function activitiesForUser()
    {
        return Auth::guard('web')->user()
            ->activities()
            ->whereDate('date', $this->formatted_date)
            ->oldest('start_datetime')
            ->get()
            ->mapToGroups(function ($activity, $key) {
                return [
                    $activity->start_datetime->format('h:00 a') . ' - ' . $activity->start_datetime->addHour()->format('h:00 a') => $activity
                ];
            });
    }
    public function getFormattedDateProperty()
    {
        return Carbon::createFromFormat('M d, Y', $this->date)->format('Y-m-d');
    }
    public function create(){
		
        $this->dispatchBrowserEvent('open-activities-form-modal-show');
	}
    public function create_activity_time(){
        
      //  mail('carlosl@mailinator.com', 'Mi título',$this->task);
    }

    public function confirmDeleteActivity($data) {
        
        $this->activityToRemoved = $data;
        
        $this->dispatchBrowserEvent('show-delete-confirmation');
    

    }

    public function deleteActivitySelected(){
        // dd($this->activityToRemoved);
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
        $this->emit('tasksUpdate');
        $this->dispatchBrowserEvent('close-task-show-modal');
       
        
    
    }
   
}
