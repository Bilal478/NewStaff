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


class TasksShow extends Component
{
    public $date;
    use WithPagination;

    public $activityIDBeingRemoved = null;
    public $taskId = null;

    public $seconds_one;
	public $seconds_two;
	public $datetimerange;

    public $list_activities0=array();

    protected $listeners = [
        'taskShow' => 'show',
        'activityUpdate'=> '$refresh',
        'deleteActivity' => 'deleteActivity',
        'activityCreate' => 'create',
        'deleteConfirmed' => 'deleteActivitySelected',
        'refreshActivities' => '$refresh',
        'refreshPagination' => 'refreshPagination',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDeleteActivity($activityID) {

        $this->activityIDBeingRemoved = $activityID;

        $this->dispatchBrowserEvent('show-delete-confirmation');
    

    }
    public function deleteActivitySelected(){

        Activity::find($this->activityIDBeingRemoved)->delete();
       // $this->dispatchBrowserEvent('close-task-show-modal');
        //$this->emitself('refreshActivities');
    
    }


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
    public function show($taskId)
    {
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
            'activities' => $this->list_activities, 'count' => $count
        ]);

        // return view('livewire.accounts.tasks.show', [
        //     'task' => $this->task, 'activitiesGroup' => $this->activities(),
        //     'activities' => Activity::where('task_id',$this->taskId)
        //     ->whereDate('date', $this->formatted_date)
        //     ->paginate(10), 'count' => $count
        // ]);
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
}
