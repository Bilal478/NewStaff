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
        return $this->startDate()->format('M d, Y') . '  -  ' . $this->endDate()->format('M d, Y');
    }

    public function getWeekDates()
    {
        return collect(CarbonPeriod::create($this->startDate(), $this->endDate()));
    }

    public function download()
    {
        PDF::loadView('pdf.report', [
            'users' => $this->getUsersReport(),
            'dates' => $this->getWeekDates(),
            'week' => $this->getWeekFormatted(),
        ])
            ->setPaper('a4', 'landscape')
            ->save(storage_path() . '/' . $this->week . '.pdf');

        return response()->download(storage_path() . '/' . $this->week . '.pdf')->deleteFileAfterSend(true);
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


        $results = Activity::join('users', 'activities.user_id', '=', 'users.id')
        ->join('projects', 'activities.project_id', '=', 'projects.id')
        ->join('tasks', 'activities.task_id', '=', 'tasks.id')
        ->whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])->where(['activities.user_id'=> Auth::user()->id])
        ->groupBy('activities.user_id', 'activities.date', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title','activities.seconds','activities.total_activity_percentage','activities.start_datetime','activities.end_datetime')
        ->selectRaw('projects.title as project_title, tasks.title as task_title, sum(activities.seconds) as seconds, activities.date, avg(activities.total_activity_percentage) as productivity,activities.start_datetime,activities.end_datetime')->get();

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
                $diffInSeconds = $endDateTime->diffInSeconds($startDateTime);

                if($diffInSeconds > 0){
                   $j++;
                    $arrayData[] = [
                        'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                        'end_time' => $result->end_datetime->format('h:i A'),
                        'date' => $result->date->format('Y-m-d'),
                        'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                        'minutes'=> $seconds_sum/60,
                        'productivity' => intval($result->productivity),
                        'project_title' => $result->project_title,
                        'task_title' => $result->task_title,
                        
                    ];
                    
                    
                    $seconds_sum = 0;
                    
                }
            }

            if(isset($results[$index+1])){
                if($results[$index+1]->date != $result->date){
                    
                    $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
                    $i+=$j;
                    $seconds_sum_of_day = 0;
                }
            }else{
                $arrayData[$i]['hours_of_day'] = CarbonInterval::seconds($seconds_sum_of_day)->cascade()->format('%H:%I:%S');
            }
           
            
        }
        return $arrayData;
    }
}