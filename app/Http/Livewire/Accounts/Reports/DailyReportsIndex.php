<?php

namespace App\Http\Livewire\Accounts\Reports;

use App\Models\Subscription;



use App\Models\Activity;
use App\Models\User;
use App\Models\Account;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;
use Illuminate\Support\Facades\DB;

class DailyReportsIndex extends Component
{
    use WithPagination;

    public $date;
    public $week;
    public $userName;
    public $search = '';
    public $login = '';
    public $user_list = '';
    public $user_id = '';
    public $show = '';
    public $newStartTime;
    public $newEndTime;
    public $startTime;
    public $endTime;
    public $activityToRemoved = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'activityUpdate' => '$refresh',
        'deleteActivity' => 'deleteActivity',
        'deleteConfirmed' => 'deleteActivitySelected',
    ];

    

    public function show($value)
    {
        if ($value != '') {
            $this->show = $value;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    // public function resetSession()
    // {
    //     session()->forget(['selected_date1']);
    // }
    public function mount()
    {
        $selectedDate = session('selected_date1', null);
        $selectedUser = session('selected_user1', null);
        if ($selectedUser) {
            $this->user_id = $selectedUser;
        }
        if ($selectedDate) {
            $this->date = $selectedDate;
        } else {
            $this->date = Carbon::today()->format('M d, Y');
        }
        $this->week = $this->getWeekFormatted();
        $departments_ids=[];
	// $this->user_list = User::orderBy('firstname')->get(['id', 'firstname', 'lastname']);
	#this->user_id = $this->users->first()->id;
            $user_login = auth()->id();
            $role=DB::select('SELECT role FROM account_user where user_id='.$user_login );
            foreach($role as $val){
                $user_role=$val->role;
            }
            if($user_role=='owner'){
            $this->user_list =User::orderBy('firstname')->get(['id', 'firstname', 'lastname']);  
            }
            else{
            $user_departments=DB::select('SELECT department_id FROM department_user where user_id='.$user_login );
            foreach($user_departments as $val){
                $departments_ids[]=$val->department_id;
            }
            $departments_user=DB::table('department_user')->whereIn('department_id', $departments_ids)->get();
            $unique_users = [];
            foreach($departments_user as $val){
                
                if(!in_array($val->user_id,$unique_users)){
                    $unique_users[] = $val->user_id;
                }
            }

            $this->user_list = User::wherein('id', $unique_users)->orderBy('firstname')->get(['id', 'firstname', 'lastname']);
            $this->login = User::where('id', $user_login)->get();
        }

    }

    public function prevWeek()
    {
        $date = Carbon::createFromFormat('M d, Y', $this->date)->subWeek();

        $this->date = $date->format('M d, Y');
        $this->week = $this->getWeekFormatted();
    }

    public function nextWeek()
    {
        $date = Carbon::createFromFormat('M d, Y', $this->date)->addWeek();

        $this->date = $date->format('M d, Y');
        $this->week = $this->getWeekFormatted();
    }

    public function updatedDate()
    {
        $this->week = $this->getWeekFormatted();
    }

    public function startDate($formatted = false)
    {
        $startDate = Carbon::createFromFormat('M d, Y', $this->date)->startOfWeek(Carbon::MONDAY);

        return $formatted ? $startDate->format('Y-m-d') : $startDate;
    }

    public function endDate($formatted = false)
    {
        $endDate = Carbon::createFromFormat('M d, Y', $this->date)->endOfWeek(Carbon::SUNDAY);

        return $formatted ? $endDate->format('Y-m-d') : $endDate;
    }

    public function getWeekFormatted()
    {
        return $this->startDate()->format('D, M d, Y') . '  -  ' . $this->endDate()->format('D, M d, Y');
    }

    public function getWeekDates()
    {
        return collect(CarbonPeriod::create($this->startDate(), $this->endDate()));
    }

    public function download()
    {
        PDF::loadView('pdf.dailyreport', [
            'users' => $this->getUsersReport(),
			'previousWeekUsers' => $this->getPreviousWeekUsersReport(),
            'userName' => $this->userName,
            'dates' => $this->getWeekDates(),
            'week' => $this->getWeekFormatted(),
        ])
            ->setPaper('a4', 'landscape')
            ->save(storage_path() .'/'.$this->userName.'_timesheet_report_' . $this->week . '.pdf');

        return response()->download(storage_path() .'/'.$this->userName.'_timesheet_report_' . $this->week .'.pdf')->deleteFileAfterSend(true);
    }

    public function render()
    {		
        // dd($this->getUsersReport());
			return view('livewire.accounts.reports.daily-reports-index', [
				'users' => $this->getUsersReport(),
				'previousWeekUsers' => $this->getPreviousWeekUsersReport(),
				'dates' => $this->getWeekDates(),
			])->layout('layouts.app', ['title' => 'Reports']);
    }

    public function getUsersReport()
    {
        if(!$this->user_id){
            $this->user_id = Auth::user()->id;
        }
        $name = User::where('id', $this->user_id)->first();
        $this->userName = $name->firstname.' '.$name->lastname;
        $results = Activity::whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])
        ->where('activities.user_id', $this->user_id)
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->select('activities.*', 
                 DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
                 DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
        ->orderBy('activities.start_datetime')
        ->get();
        // $results = Activity::join('users', 'activities.user_id', '=', 'users.id')
        // ->join('projects', 'activities.project_id', '=', 'projects.id')
        // ->leftJoin('tasks', function ($join) {
        //     $join->on('activities.task_id', '=', 'tasks.id')
        //         ->orWhereNull('activities.task_id');
        // })
        // ->whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])->where(['activities.user_id'=> $this->user_id])
        // ->groupBy('activities.id','activities.user_id', 'activities.account_id','activities.date', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title','activities.seconds','activities.total_activity_percentage','activities.start_datetime','activities.end_datetime')
        // ->selectRaw('activities.id,activities.user_id,activities.account_id,activities.task_id,activities.project_id,projects.title as project_title, sum(activities.seconds) as seconds, activities.date, avg(activities.total_activity_percentage) as productivity,activities.start_datetime,activities.end_datetime,
        // CASE
        // WHEN activities.task_id IS NULL THEN "No to-do"
        // ELSE tasks.title
        // END AS task_title')->orderBy('activities.start_datetime')->get();
        $ss = [];
        $arrayData = [];
        $seconds_sum_of_day = 0;
        $seconds_sum = 0;
        $total_productivity=0;
        $idle_percentage=0;
        $manual_percentage=0;
        $count=0; 
        $start_time_index=0;
        $i=0;
        $j=0;
        foreach($results as $index=>$result){
            // dd($result);
            $account=Account::where('id',$result->account_id)->first();
            $accountName=$account->name;
            $startDateTime = Carbon::parse($result->end_datetime);
            $seconds_sum_of_day += $result->seconds;
            $total_productivity += $result->total_activity_percentage;
            if($result->total_activity_percentage==0){
                $idle_percentage++;
            }
            if($result->is_manual_time==1){
                $manual_percentage++;
            }
            $count++;
            if(isset($results[$index+1])){
                if($seconds_sum == 0){
                    $start_time_index = $index;
                }
                if($results[$index]->start_datetime==$results[$index+1]->start_datetime){
                // $seconds_sum += $result->seconds;
                    continue;
                }
                $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
                
                $seconds_sum += $result->seconds;
                $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
                $ss[]=$diffInSeconds;
                if($diffInSeconds > 0 || ($result->task_id != $results[$index+1]->task_id) ){
                   $j++;
                    $arrayData[] = [
                        'user_id' => $result->user_id,
                        'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                        'end_time' => $result->end_datetime->format('h:i A'),
                        'date' => $result->date->format('Y-m-d'),
                        'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                        'minutes'=> $seconds_sum/60,
                        // 'productivity' => intval($result->productivity),
                        'productivity' => intval(round(($total_productivity/$count),0)),
                        'project_id' => $result->project_id,
                        'project_title' => $result->project_title,
                        'task_id' => $result->task_id,
                        'account_id' => $result->account_id,
                        'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',
                        'account_name' =>  $accountName,
                        'manual_percentage' => intval(round(($manual_percentage/$count)*100),0),
                        'idle_percentage' => intval(round(($idle_percentage/$count)*100),0),
                    ];
                    
                    
                    $seconds_sum = 0;
                    $total_productivity=0;
                    $idle_percentage=0;
                    $manual_percentage=0;
                    $count=0; 
                    
                }
            }

            // if(isset($results[$index+1])){
            //     if($results[$index+1]->date != $result->date){
                    
            //         $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
            //         $i+=$j;
            //         $seconds_sum_of_day = 0;
            //     }
            // }else{
            //     $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
            // }
           
            
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
                // 'productivity' => intval($lastResult->productivity),
                'productivity' => intval(round(($total_productivity/$count),0)),
                'project_id' => $lastResult->project_id,
                'project_title' => $lastResult->project_title,
                'task_id' => $lastResult->task_id,
                'account_id' => $lastResult->account_id,
                'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
                'account_name' =>  $accountName,
                'manual_percentage' => intval(round(($manual_percentage/$count)*100),0),
                'idle_percentage' => intval(round(($idle_percentage/$count)*100),0),
            ];
    }
        
        return $arrayData;
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
        $this->emit('activityUpdate');
        // Activity::find($this->activityIDBeingRemoved)->delete();
       // $this->dispatchBrowserEvent('close-task-show-modal');
        //$this->emitself('refreshActivities');
    
    }
    public function getPreviousWeekUsersReport()
    {
        if(!$this->user_id){
            $this->user_id = Auth::user()->id;
        }
        $name = User::where('id', $this->user_id)->first();
        $this->userName = $name->firstname.' '.$name->lastname;
        $startDate = Carbon::createFromFormat('M d, Y', $this->date)->subWeek()->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromFormat('M d, Y', $this->date)->subWeek()->endOfWeek(Carbon::SUNDAY);
        $results = Activity::whereBetween('activities.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
        ->where('activities.user_id', $this->user_id)
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->select('activities.*', 
                 DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
                 DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
        ->orderBy('activities.start_datetime')
        ->get();
        // $results = Activity::join('users', 'activities.user_id', '=', 'users.id')
        // ->join('projects', 'activities.project_id', '=', 'projects.id')
        // ->leftJoin('tasks', function ($join) {
        //     $join->on('activities.task_id', '=', 'tasks.id')
        //         ->orWhereNull('activities.task_id');
        // })
        // ->whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])->where(['activities.user_id'=> $this->user_id])
        // ->groupBy('activities.id','activities.user_id', 'activities.account_id','activities.date', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title','activities.seconds','activities.total_activity_percentage','activities.start_datetime','activities.end_datetime')
        // ->selectRaw('activities.id,activities.user_id,activities.account_id,activities.task_id,activities.project_id,projects.title as project_title, sum(activities.seconds) as seconds, activities.date, avg(activities.total_activity_percentage) as productivity,activities.start_datetime,activities.end_datetime,
        // CASE
        // WHEN activities.task_id IS NULL THEN "No to-do"
        // ELSE tasks.title
        // END AS task_title')->orderBy('activities.start_datetime')->get();
        $ss = [];
        $arrayData = [];
        $seconds_sum_of_day = 0;
        $seconds_sum = 0;
        $total_productivity=0;
        $count=0; 
        $start_time_index=0;
        $i=0;
        $j=0;
        foreach($results as $index=>$result){
            // dd($result);
            $account=Account::where('id',$result->account_id)->first();
            $accountName=$account->name;
            $startDateTime = Carbon::parse($result->end_datetime);
            $seconds_sum_of_day += $result->seconds;
            $total_productivity += $result->total_activity_percentage;
            $count++;
            if(isset($results[$index+1])){
                if($seconds_sum == 0){
                    $start_time_index = $index;
                }
                if($results[$index]->start_datetime==$results[$index+1]->start_datetime){
                // $seconds_sum += $result->seconds;
                    continue;
                }
                $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
                
                $seconds_sum += $result->seconds;
                $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
                $ss[]=$diffInSeconds;
                if($diffInSeconds > 0 || ($result->task_id != $results[$index+1]->task_id) ){
                   $j++;
                    $arrayData[] = [
                        'user_id' => $result->user_id,
                        'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                        'end_time' => $result->end_datetime->format('h:i A'),
                        'date' => $result->date->format('Y-m-d'),
                        'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                        'minutes'=> $seconds_sum/60,
                        // 'productivity' => intval($result->productivity),
                        'productivity' => intval(round(($total_productivity/$count),0)),
                        'project_id' => $result->project_id,
                        'project_title' => $result->project_title,
                        'task_id' => $result->task_id,
                        'account_id' => $result->account_id,
                        'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',
                        'account_name' =>  $accountName,
                        'is_manual_time' =>  $result->is_manual_time,
                    ];
                    
                    
                    $seconds_sum = 0;
                    $total_productivity=0;
                    $count=0; 
                    
                }
            }

            // if(isset($results[$index+1])){
            //     if($results[$index+1]->date != $result->date){
                    
            //         $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
            //         $i+=$j;
            //         $seconds_sum_of_day = 0;
            //     }
            // }else{
            //     $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
            // }
           
            
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
                // 'productivity' => intval($lastResult->productivity),
                'productivity' => intval(round(($total_productivity/$count),0)),
                'project_id' => $lastResult->project_id,
                'project_title' => $lastResult->project_title,
                'task_id' => $lastResult->task_id,
                'account_id' => $lastResult->account_id,
                'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
                'account_name' =>  $accountName,
                'is_manual_time' =>  $result->is_manual_time,
            ];
    }
        
        // return $arrayData;
    $users=$arrayData;
    $totalDurationInSeconds = collect($users)->sum(function ($item) {
        // Convert duration to seconds and sum them up
        return strtotime($item['duration']) - strtotime('00:00:00');
    });
    
    $hours = floor($totalDurationInSeconds / 3600);
    $minutes = floor(($totalDurationInSeconds % 3600) / 60);
    $seconds = $totalDurationInSeconds % 60;
    
    $totalDurationFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    $totalProductivity = collect($users)->sum('productivity');

    // Calculate average productivity
    $averageProductivity = count($users) > 0 ? $totalProductivity / count($users) : 0;

    // Format average productivity as needed
    $averageProductivityFormatted = number_format($averageProductivity); // Format with two decimal places
    return [$totalDurationFormatted,$averageProductivityFormatted];
    }
}
