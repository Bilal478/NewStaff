<?php

namespace App\Http\Livewire\Accounts\Reports;

use App\Models\Subscription;



use App\Models\Activity;
use App\Models\User;
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

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'activityUpdate' => '$refresh',
        'deleteActivity' => 'deleteActivity',
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

    public function mount()
    {
        $this->date = Carbon::today()->format('M d, Y');
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
        $results = Activity::join('users', 'activities.user_id', '=', 'users.id')
        ->join('projects', 'activities.project_id', '=', 'projects.id')
        ->leftJoin('tasks', function ($join) {
            $join->on('activities.task_id', '=', 'tasks.id')
                ->orWhereNull('activities.task_id');
        })
        ->whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])->where(['activities.user_id'=> $this->user_id])
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
                if($diffInSeconds > 0 || ($result->task_id != $results[$index+1]->task_id) ){
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
}
