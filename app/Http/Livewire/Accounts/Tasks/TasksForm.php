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
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

use DateTime;
use Illuminate\Support\Facades\Auth;

class TasksForm extends Component
{
    use AuthorizesRequests, Notifications;

    public $projects;
    public $start_datetime;
    public $end_datetime;
    public $seconds;
    public $seconds_two;
    public $next_day_seconds;
    public $teams;
    public $departments;
    public $users = [];
    public $isEditing = false;
    public $inProject = false;
    public $inTeam = false;
    public $inDepartment = false;
    public $addTimeNextDay;

    public $title = '';
    public $description = '';
    public $project_id = null;
    public $team_id = null;
    public $department_id = null;
    public $due_date = null;
    public $user_id = '';
    public $task_id = null;
    public $completed = 'false';
    public $tracking_time = null;
    public $activityId = null;
    public $activities = [];
    public $var = [];
    public $datetimerange;
    public $tasksDropdownOptions = [];
    public $flag;


    protected $listeners = [
        'projectsUpdate' => '$refresh',
        'taskCreate' => 'create',
        'tasksUpdate' => '$refresh',
        'activityUpdate' => '$refresh',
        'activityCreate' => 'create2',

    ];

    protected $rules = [
        'title' => 'required|string|max:250',
        'description' => 'required|string|max:500',
        'due_date' => 'date_format:"M d, Y"|nullable',
        'project_id' => 'required',
        'user_id' => 'required',
        // 'task_id' => 'required',
        // 'team_id' => 'required',
        // 'department_id' => 'required',
        'completed' => 'nullable',
    ];

    protected $messages = [
        'project_id.required' => 'The project is required.',
    ];

    public function updatedUserId($value)
    {
        $this->user_id = $value;
        if (!empty($value)) {
          
            $this->tasksDropdownOptions = Task::where('user_id', $value)->get();
        } else {
            $this->tasksDropdownOptions = []; // Reset the options when no user is selected
        }
        $this->emit('tasksUpdated',json_encode($this->tasksDropdownOptions));
        
        
    }

    public function mount($project = null, $team = null, $department = null)
    {
        if(Auth::guard('web')->user()->isOwner()){
            $this->projects = Project::orderBy('title')->get(['id', 'title']);
        }
        else{
            $this->projects = Auth::guard('web')->user()
            ->projects()->orderBy('title')->get();
            
        }
        $this->teams = Team::orderBy('title')->get(['id', 'title']);
        $this->departments = Department::orderBy('title')->get(['id', 'title']);

        if ($project) {
            $this->project_id = $project->id;
            $this->inProject = true;
        }

        if ($team) {
            $this->team_id = $team->id;
            $this->inTeam = true;
        }

        if ($department) {
            $this->department_id = $department->id;
            $this->inDepartment = true;
        }
    }

    public function updatedActivityId()
    {
        $this->tracking_time = gmdate("H:i:s", Activity::find($this->activityId)->seconds);
    }

    public function updatedProjectId()
    {
        $this->users = User::inProject($this->project_id)->get();
        $this->reset(['user_id']);
    }

    public function create()
    {
        if ($this->inProject) {
            $this->reset(['title', 'description', 'due_date', 'user_id', 'task_id', 'team_id', 'department_id']);
            $this->users = User::inProject($this->project_id)->get();
        } elseif ($this->inTeam) {
            $this->reset(['title', 'description', 'due_date', 'user_id', 'task_id', 'project_id', 'department_id']);
            $this->users = User::inTeam($this->team_id)->get();
        } elseif ($this->inDepartment) {
            $this->reset(['title', 'description', 'due_date', 'user_id', 'task_id', 'team_id', 'project_id']);
            $this->users = User::inDepartment($this->department_id)->get();
        } else {
            $this->reset(['title', 'description', 'due_date', 'project_id', 'user_id', 'task_id', 'team_id', 'department_id']);
        }

        $this->isEditing = false;
        $this->showFormModal();
    }

    public function save()
    {

        $validated = $this->validate();
        $deprtment_id = Project::where('id',$validated['project_id'])->pluck('department_id')->first();


        $validated['user_id'] = $validated['user_id'] ?: null;
        if ($validated['completed'] == 'true') {
            $validated['completed'] = 1;
        }
        if ($validated['completed'] == 'false') {
            $validated['completed'] = 0;
        }
        if ($this->isEditing) {
            sscanf($this->tracking_time, "%d:%d:%d", $hours, $minutes, $seconds);
            $time = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
            Activity::find($this->activityId)->update(['seconds' => $time]);
        }
        $validated['department_id'] = $deprtment_id;

        $this->isEditing
            ? $this->task->update($validated)
            : $TaskCreate = Task::create($validated);

        $this->emit('tasksUpdate');
        $this->dispatchBrowserEvent('close-task-form-modal');

        $this->isEditing
            ? $this->toast('Task Updated', "Task has been updated.")
            : $this->toast('Task Created', "Task has been created.");
    }
    public function create_activity(Task $task)
    {
        if (empty($this->task_id)) {
            $this->toast('Task Not Selected', 'Please select a task before creating an activity.', 'error');
            return;
        }
        if($this->next_day_seconds){
            $this->seconds_two=$this->next_day_seconds;
        }
        $temp = substr($this->seconds, 0, 8);
        $temp_two = substr($this->seconds_two, 0, 8);

        list($hours, $minutes, $seconds) = explode(':', $temp);
        $hour_in_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

        list($hours_two, $minutes_two, $seconds_two) = explode(':', $temp_two);
        $hour_in_seconds_two = ($hours_two * 3600) + ($minutes_two * 60) + $seconds_two;
         // Adjust start time to nearest lower 10-minute interval
         $start_minutes_adjustment = $minutes % 10;
         if ($start_minutes_adjustment != 0) {
            //  $hour_in_seconds -= $start_minutes_adjustment * 60;
             $start_datetime = $this->datetimerange . ' ' . date('H:i:s', strtotime("-$start_minutes_adjustment minutes", strtotime($temp)));
         } else {
             $start_datetime = $this->datetimerange . ' ' . $this->seconds;
         }
     
         // Adjust end time to nearest higher 10-minute interval
         $end_minutes_adjustment = $minutes_two % 10;
         if ($minutes_two % 10 != 0) {
            //  $hour_in_seconds_two += $end_minutes_adjustment * 60;
             $end_datetime = $this->datetimerange . ' ' . date('H:i:s', strtotime("+$end_minutes_adjustment minutes", strtotime($temp_two)));
         } else {
             $end_datetime = $this->datetimerange . ' ' . $this->seconds_two;
         }
        // $start_datetime = $this->datetimerange . ' ' . $this->seconds;
        // $end_datetime = $this->datetimerange . ' ' . $this->seconds_two;
        $time =  ($hour_in_seconds_two - $hour_in_seconds) / 600;
        
        $fullIntervals = floor($time); // Number of full 10-minute intervals
        $fractionalPart = $time - $fullIntervals; // Fractional part
        $temp_activities = Activity::where('user_id', $this->task->user_id)->where('date', $this->datetimerange)->get();
        if($hour_in_seconds > $hour_in_seconds_two){
            if($hour_in_seconds_two==0){
                $time =  (86400 - $hour_in_seconds) / 600;
            }
            else{
            $time =  ((86400 - $hour_in_seconds) / 600) + ($hour_in_seconds_two/600);
            }
        for ($i = 0; $i < $time; $i++) {

        $temp = strtotime('+' . $i . '0 minutes ', strtotime(substr($start_datetime, 0, 19)));
        $new_start_time = date('Y-m-d H:i:s', $temp);
        $new_start_time_two = date('H:i:s', $temp);
        if ($new_start_time_two == '00:00:00') {
            $carbonDate = Carbon::parse($this->datetimerange);
            $carbonDate->addDay();
            $this->datetimerange = $carbonDate->toDateTimeString(); // Update the string value
        }

        $temp_two = strtotime('+' . $i . '0 minutes ', strtotime(substr($start_datetime, 0, 19)));
        $new_end_time = date('Y-m-d H:i:s', $temp_two);
        $temp_two_final = strtotime('+10 minutes ', strtotime($new_end_time));
        $end_time = date('Y-m-d H:i:s', $temp_two_final);
        //Find if there is a activity in the period of time
        $data = DB::table('activities')
            ->where('start_datetime', '>=', $new_start_time)
            ->where('end_datetime', '<=', $end_time)
            ->where('user_id', '=', $this->task->user_id)
            // ->where('project_id', '=', $this->task->project_id)
            ->where('account_id', '=', $this->task->account_id)
            // ->where('task_id', '=', $this->task->id)
            ->whereNull('deleted_at')
            ->get();

        //Count rows finded in the period of time
        $rows = count($data);

        if($rows>0)
        {
            $this->toast('Activity can not be created', "There is an activity in this period of time TimeStart: " . $new_start_time .
            " - End time: " . $end_time,'error',15000);
            return false;  
        }

        if ($rows == 0) {
            DB::table('activities')->insert([
                'from' => 621,
                'to' => 1200,
                'seconds' => 600, //$hour_in_seconds_two - $hour_in_seconds,
                'start_datetime' => $new_start_time, // substr($start_datetime,0,19),
                'date' => $this->datetimerange,
                'keyboard_count' => 100,
                'mouse_count' => 40,
                'total_activity' => 100,
                'total_activity_percentage' => 100,
                'task_id' => $this->task_id,
                'end_datetime' => $end_time, //substr($end_datetime,0,19),
                'user_id' => $this->task->user_id,
                'project_id' => $this->task->project_id,
                'account_id' => $this->task->account_id,
                'created_at' =>  $new_start_time, //substr($start_datetime,0,19), 
                'updated_at' =>  $new_start_time, //substr($start_datetime,0,19)
                'is_manual_time' =>  1,
            ]);

            $temp = DB::table('activities')->select('id')
                ->orderBy('id', 'DESC')
                ->take(5)
                ->get();

            $id_activity = $temp[0]->id;

            DB::table('screenshots')->insert([
                'path' => '00/1234567890.png',
                'activity_id' => $id_activity,
                'account_id' => $this->task->account_id,
                'created_at' => $this->datetimerange,
                'updated_at' => $this->datetimerange
            ]);
        }
    }
}
else{
        for ($i = 0; $i < $time; $i++) {

            $temp = strtotime('+' . $i . '0 minutes ', strtotime(substr($start_datetime, 0, 19)));
            $new_start_time = date('Y-m-d H:i:s', $temp);
            $temp_two = strtotime('+' . $i . '0 minutes ', strtotime(substr($start_datetime, 0, 19)));
            $new_end_time = date('Y-m-d H:i:s', $temp_two);
            $temp_two_final = strtotime('+10 minutes ', strtotime($new_end_time));
            $end_time = date('Y-m-d H:i:s', $temp_two_final);
            //Find if there is a activity in the period of time
            $data = DB::table('activities')
                ->where('start_datetime', '>=', $new_start_time)
                ->where('end_datetime', '<=', $end_time)
                ->where('user_id', '=', $this->task->user_id)
                // ->where('project_id', '=', $this->task->project_id)
                ->where('account_id', '=', $this->task->account_id)
                // ->where('task_id', '=', $this->task->id)
                ->whereNull('deleted_at')
                ->get();

            //Count rows finded in the period of time
            $rows = count($data);

            if($rows>0)
            {
                $this->toast('Activity can not be created', "There is an activity in this period of time TimeStart: " . $new_start_time .
                " - End time: " . $end_time,'error',15000);
                return false;  
            }

            if ($rows == 0) {
                if ($i == 0 && $start_minutes_adjustment != 0) {
                    $fractional_start_time = date('Y-m-d H:i:s', strtotime("+$start_minutes_adjustment minutes", strtotime($new_start_time)));
                    DB::table('activities')->insert([
                        'from' => 600,
                        'to' => 1200,
                        'seconds' => 600 - ($start_minutes_adjustment * 60),
                        'start_datetime' => $start_datetime,
                        'date' => $this->datetimerange,
                        'keyboard_count' => 100,
                        'mouse_count' => 40,
                        'total_activity' => 100,
                        'total_activity_percentage' => 100,
                        'task_id' => $this->task_id,
                        'end_datetime' => $end_time,
                        'user_id' => $this->task->user_id,
                        'project_id' => $this->task->project_id,
                        'account_id' => $this->task->account_id,
                        'created_at' => $fractional_start_time,
                        'updated_at' => $fractional_start_time,
                        'is_manual_time' => 1,
                    ]);
                }
                else if ($i == $fullIntervals - 1 && $end_minutes_adjustment != 0) {
                    $fractional_end_time = date('Y-m-d H:i:s', strtotime("-$end_minutes_adjustment minutes", strtotime($end_time)));
                    DB::table('activities')->insert([
                        'from' => 600,
                        'to' => 1200,
                        'seconds' =>($end_minutes_adjustment * 60),
                        'start_datetime' => $new_start_time,
                        'date' => $this->datetimerange,
                        'keyboard_count' => 100,
                        'mouse_count' => 40,
                        'total_activity' => 100,
                        'total_activity_percentage' => 100,
                        'task_id' => $this->task_id,
                        'end_datetime' => $end_time,
                        'user_id' => $this->task->user_id,
                        'project_id' => $this->task->project_id,
                        'account_id' => $this->task->account_id,
                        'created_at' => $new_start_time,
                        'updated_at' => $new_start_time,
                        'is_manual_time' => 1,
                    ]);
                } else {
                    // Insert full 10-minute intervals
                    DB::table('activities')->insert([
                        'from' => 600,
                        'to' => 1200,
                        'seconds' => 600,
                        'start_datetime' => $new_start_time,
                        'date' => $this->datetimerange,
                        'keyboard_count' => 100,
                        'mouse_count' => 40,
                        'total_activity' => 100,
                        'total_activity_percentage' => 100,
                        'task_id' => $this->task_id,
                        'end_datetime' => $end_time,
                        'user_id' => $this->task->user_id,
                        'project_id' => $this->task->project_id,
                        'account_id' => $this->task->account_id,
                        'created_at' => $new_start_time,
                        'updated_at' => $new_start_time,
                        'is_manual_time' => 1,
                    ]);
                }

                $temp = DB::table('activities')->select('id')
                    ->orderBy('id', 'DESC')
                    ->take(5)
                    ->get();

                $id_activity = $temp[0]->id;

                DB::table('screenshots')->insert([
                    'path' => '00/1234567890.png',
                    'activity_id' => $id_activity,
                    'account_id' => $this->task->account_id,
                    'created_at' => $this->datetimerange,
                    'updated_at' => $this->datetimerange
                ]);
            }
        }
    }

        $this->dispatchBrowserEvent('close-activities-form-modal');
        $this->dispatchBrowserEvent('close-activity-modal');

        $this->toast('Activity Created', "The Activity has been created from " .
            $this->seconds . " to " . $this->seconds_two);
            $this->seconds='';
            $this->seconds_two='';
            $this->task_id = null;
            $this->emit('activityUpdate');
            $this->emit('tasksUpdate');
    }

    public function getTaskProperty()
    {
        return Task::find($this->task_id);
    }

    public function getProjectProperty()
    {
        return Project::find($this->project_id);
    }
    public function showFormModal()
    {
        $this->dispatchBrowserEvent('open-task-form-modal');
    }

    public function render()
    {
        return view('livewire.accounts.tasks.form', ['usersIn' => User::inProject($this->project_id)->get(), 'project' => $this->project]);
    }
    public function create2($date,$userId,$flag)
    { 
        $this->datetimerange = date('Y-m-d', strtotime($date));
        $this->tasksDropdownOptions = Task::where('user_id', $userId)->get();
        $this->emit('tasksUpdated',json_encode($this->tasksDropdownOptions));
        $this->user_id=$userId;
        $this->flag=$flag;
        $this->dispatchBrowserEvent('open-activities-form-modal');
    }

}
