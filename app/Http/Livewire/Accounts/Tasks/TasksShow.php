<?php

namespace App\Http\Livewire\Accounts\Tasks;

use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Task;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

class TasksShow extends Component
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
        'taskShow' => 'show',
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

    // public function confirmDeleteActivity($activityID) {

    //     $this->activityIDBeingRemoved = $activityID;

    //     $this->dispatchBrowserEvent('show-delete-confirmation');
    

    // }
    // public function deleteActivitySelected(){

    //     Activity::find($this->activityIDBeingRemoved)->delete();
    //    // $this->dispatchBrowserEvent('close-task-show-modal');
    //     //$this->emitself('refreshActivities');
    
    // }


    public function deleteActivity($id)
    {   
        Activity::find($id)->delete();
        //$this->dispatchBrowserEvent('close-task-show-modal');
       // $this->emitself('refreshActivities');
        
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
    public function show($taskId,$userId)
    {
        $this->user_id = $userId;
        $this->taskId = $taskId;
        $this->dispatchBrowserEvent('open-task-show-modal');
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

        // $number_activities = Activity::where('task_id',$this->taskId)
        // ->whereDate('date', $this->formatted_date)
        // ->paginate(10);

        
       // $count = count($number_activities);
        $count = count($this->list_activities);

        return view('livewire.accounts.tasks.show', [
            'task' => $this->task, 'activitiesGroup' => $this->activities(),
            'activities' => $this->list_activities, 'count' => $count,
            'selectedDateRecord' => $this->selectedDateActivity()
        ]);

        // return view('livewire.accounts.tasks.show', [
        //     'task' => $this->task, 'activitiesGroup' => $this->activities(),
        //     'activities' => Activity::where('task_id',$this->taskId)
        //     ->whereDate('date', $this->formatted_date)
        //     ->paginate(10), 'count' => $count
        // ]);
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
        ->join('projects', 'activities.project_id', '=', 'projects.id')
        ->leftJoin('tasks', function ($join) {
            $join->on('activities.task_id', '=', 'tasks.id')
                ->orWhereNull('activities.task_id');
        })
        ->where('activities.date',$startDate)->where(['activities.user_id'=> $this->user_id,'activities.task_id'=> $this->taskId])
        ->groupBy('activities.id','activities.user_id', 'activities.account_id','activities.date', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title','activities.seconds','activities.total_activity_percentage','activities.start_datetime','activities.end_datetime')
        ->selectRaw('activities.id,activities.user_id,activities.account_id,activities.task_id,activities.project_id,projects.title as project_title, sum(activities.seconds) as seconds, activities.date, avg(activities.total_activity_percentage) as productivity,activities.start_datetime,activities.end_datetime,
        CASE
        WHEN activities.task_id IS NULL THEN "No to-do"
        ELSE tasks.title
        END AS task_title')->orderBy('activities.start_datetime')->get();
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
