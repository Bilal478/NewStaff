<?php

namespace App\Http\Livewire\Accounts\Reports;

use App\Models\Account;
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

class ReportsIndex extends Component
{
    use WithPagination;

    public $date;
    public $userName;
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
    // public function resetSession()
    // {
    //     session()->forget(['selected_date']);
    // }
    public function mount()
    {
        $selectedDate = session('selected_date', null);
        $selectedUser = session('selected_user', null);
        if ($selectedUser) {
            $this->user_id = $selectedUser;
        }
        if ($selectedDate) {
            $this->date = $selectedDate;
        } else {
            $this->date = Carbon::today()->format('M d, Y');
        }
        $this->week = $this->getWeekFormatted();
        $departments_ids = [];
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
        PDF::loadView('pdf.report', [
            'users' => $this->getUsersReport(),
            'userName' => $this->userName,
            'dates' => $this->getWeekDates(),
            'week' => $this->getWeekFormatted(),
            'dailyTotalsFormatted' => $this->dailyTotal()
        ])
            ->setPaper('a4', 'landscape')
            ->save(storage_path() .'/'.$this->userName.'_timesheet_report_' . $this->week . '.pdf');

        return response()->download(storage_path() .'/'.$this->userName.'_timesheet_report_' . $this->week . '.pdf')->deleteFileAfterSend(true);
    }

    public function downloadCsv()
    {
        $totalTimeInSeconds = 0;

        foreach ($this->getUsersReport() as $userName => $activity) {
            $time = $activity['total'];
            $timeParts = explode(':', $time);
            $hours = (int) $timeParts[0];
            $minutes = (int) $timeParts[1];
            $seconds = (int) $timeParts[2];
            $totalTimeInSeconds += $hours * 3600 + $minutes * 60 + $seconds;
        }

        $hours = floor($totalTimeInSeconds / 3600);
        $minutes = floor(($totalTimeInSeconds % 3600) / 60);
        $seconds = $totalTimeInSeconds % 60;
        $totalTimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $totalHours = $hours + ($minutes / 60) + ($seconds / 3600);
        $finalFormattedTime = number_format($totalHours, 2);
        list($wholeHours, $fractional) = explode('.', $finalFormattedTime);
        $totalDigitalTime = sprintf('%02d.%02d', $wholeHours, $fractional);
        
        $fileName = $this->userName . '_timesheet_report_' . $this->week . '.csv';
        $filePath = storage_path($fileName);
    
        // Open a file handle
        $file = fopen($filePath, 'w');

        $nbsp = "\xC2\xA0"; // UTF-8 non-breaking space

        fputcsv($file, [
            '', '', '', '', '', '', '', '', '', '',
            str_repeat($nbsp, 30) . 'Total Time: ' . ($totalTimeFormatted ?? 'N/A') . ' ' . str_repeat($nbsp, 30) . 'Digital Time: ' . ($totalDigitalTime ?? 'N/A')
        ]);

        // Add the header row
        fputcsv($file, [
            'Member', 'Organization', 'Time Zone', 'Project', 'Start Time', 'Stop Time', 'Duration', 
            'Productivity', 'Idle', 'Manual', 'Type',
        ]);
    
        // Process each date in the week
        foreach ($this->getWeekDates() as $date) {
            // Filter users' activities by the current date
            $activities = collect($this->getUsersReportForCsv())->filter(function ($item) use ($date) {
                return $item['date'] === $date->format('Y-m-d');
            });
    
            // Remove activities with zero duration
            $nonZeroActivities = $activities->filter(function ($item) {
                $durationInSeconds = strtotime($item['duration']) - strtotime('00:00:00');
                return $durationInSeconds > 0;
            });
    
            // Skip the date if all activities have zero duration
            if ($nonZeroActivities->isEmpty()) {
                continue;
            }
    
            // Calculate the total duration for the date
            $totalDurationInSeconds = $nonZeroActivities->sum(function ($item) {
                return strtotime($item['duration']) - strtotime('00:00:00');
            });
    
            $totalDurationFormatted = gmdate('H:i:s', $totalDurationInSeconds);
    
            // Add a summary row for the date
            // fputcsv($file, [
            //     $date->format('D, M d, Y'), '', '', '', '', '', $totalDurationFormatted, '', '', '', ''
            // ]);
    
            // Add detailed rows for each activity with non-zero duration
            foreach ($nonZeroActivities as $activity) {
                fputcsv($file, [
                    $this->userName, // Leave the date column empty for detailed rows
                    $activity['account_name'],
                    'America/Chicago',
                    $activity['project_title'],
                    Carbon::createFromFormat('Y-m-d H:i A', $activity['date'] . ' ' . $activity['start_time'])
                    ->format('D, M d, Y g:i A'), // Combine date and time for start time
                    Carbon::createFromFormat('Y-m-d H:i A', $activity['date'] . ' ' . $activity['end_time'])
                    ->format('D, M d, Y g:i A'), // Combine date and time for end time
                    $activity['duration'],
                    $activity['productivity'] . '%',
                    $activity['idle_percentage'] . '%',
                    $activity['manual_percentage'] . '%',
                    $activity['manual_percentage'] == 100 ? 'Manual' : 
                        ($activity['manual_percentage'] == 0 ? 'Tracked' : 
                        $activity['manual_percentage'] . '% Manual / ' . (100 - $activity['manual_percentage']) . '% Tracked'),
                ]);
            }
        }
    
        // Close the file handle
        fclose($file);
    
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {		
        // dd($this->getUsersReport());
			return view('livewire.accounts.reports.index', [
				'users' => $this->getUsersReport(),
				'dates' => $this->getWeekDates(),
                'dailyTotalsFormatted' => $this->dailyTotal()
			])->layout('layouts.app', ['title' => 'Reports']);
    }
    public function dailyTotal()
    {
        $dailyTotals = array_fill(0, count($this->getWeekDates()), 0);
            foreach ($this->getUsersReport() as $activity) {
                foreach ($activity['days'] as $dayIndex => $day) {
                    $dailyTotals[$dayIndex] += $day['seconds2'] ?? 0;
                }
            }
        return array_map(function ($seconds) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $secs = $seconds % 60;
         return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }, $dailyTotals);
    }
    public function getUsersReport()
    { 
         if(!$this->user_id){
            $this->user_id = Auth::user()->id;
        }
        $name = User::where('id', $this->user_id)->first();
        if($name){
            $this->userName = $name->firstname.' '.$name->lastname;
        } 
    return Activity::join('users', 'activities.user_id', '=', 'users.id')
        ->leftjoin('projects', 'activities.project_id', '=', 'projects.id')
        ->leftJoin('tasks', function ($join) {
            $join->on('activities.task_id', '=', 'tasks.id')
                ->orWhereNull('activities.task_id');
        })
        ->groupBy('activities.user_id', 'activities.date', 'users.firstname', 'users.lastname', 'activities.task_id', 'activities.project_id', 'projects.title', 'tasks.title','activities.account_id')
        ->selectRaw('CONCAT(users.firstname, " ", users.lastname) AS full_name, sum(activities.seconds) as seconds, avg(activities.total_activity_percentage) as productivity, activities.date, users.firstname, users.lastname, activities.user_id, activities.task_id, activities.project_id, projects.title as project_title, activities.account_id, 
        CASE
        WHEN activities.task_id IS NULL THEN "No to-do"
        ELSE tasks.title
        END AS task_title')->whereBetween('activities.date', [$this->startDate(true), $this->endDate(true)])
        ->when($this->user_id, function($query) {
    return $query->where('activities.user_id', $this->user_id);
    })
        ->when(Auth::guard('web')->user()->isNotOwnerOrManager(), function ($query) {
    return $query->where('activities.user_id', Auth::user()->id);
    })
        ->get()
        ->groupByUserName()
        ->mapActivitiesStatsByDates($this->getWeekDates()); 
    }

    public function getUsersReportForCsv()
    {
        if(!$this->user_id){
            $this->user_id = Auth::user()->id;
        }
        $name = User::where('id', $this->user_id)->first();
        if($name){
            $this->userName = $name->firstname.' '.$name->lastname;
        }
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
}
