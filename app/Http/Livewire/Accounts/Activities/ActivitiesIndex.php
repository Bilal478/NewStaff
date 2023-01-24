<?php

namespace App\Http\Livewire\Accounts\Activities;


use App\Models\Subscription;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Activity;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\This;

class ActivitiesIndex extends Component
{
    public $date;
    public $user_id = '';
    public $users = '';
    public $login = '';
    public $timeToday = 0;
    public $timeYesterday = 0;
    public $totalPreviuosTime = 0;
    public $totalPreviuosTimeState = '';
    public $todayActivityPer = '';
    public $weekActivityPer = '';

    protected $listeners = [
        'activityUpdate' => '$refresh',
        'activityCreate' => 'create',
        'activityDelete' => 'delete',
    ];

    public function mount()
    {
        // $this->date = Carbon::today()->format('M d, Y');
        // $this->users = User::orderBy('firstname')->get(['id', 'firstname', 'lastname']);
        // $this->user_id = $this->users->first()->id;

    }

    public function addDay()
    {
        $this->date = Carbon::createFromFormat('M d, Y', $this->date)->addDay()->format('M d, Y');
    }

    public function subDay()
    {
        $this->date = Carbon::createFromFormat('M d, Y', $this->date)->subDay()->format('M d, Y');
    }

    public function render()
    {
        if ($this->user_id) {
            Session::put('user_id', $this->user_id);
        } else {
            if ($this->user_id == 'all') {
                Session::forget('user_id');
                $this->user_id = '';
            } else {
                $this->user_id = Session::get('user_id');
                Session::forget('user_id');
                Session::put('user_id', $this->user_id);
            }
            $user_login = auth()->id();
            $this->users = User::where('id', '!=', $user_login)->get(['id', 'firstname', 'lastname']);

            $this->login = User::where('id', $user_login)->get();
        }
        if ($this->date) {
            Session::put('date', $this->date);
        } else {
            $this->date = Session::get('date', Carbon::today()->format('M d, Y'));
            Session::forget('date');
            Session::put('date', $this->date);
        }
        $this->activitiesTime();

        return view('livewire.accounts.activities.index', [
            'activitiesGroup' => $this->activities(),
            'WeeklyHours' => $this->calculateWeeklyHours(),
        ])->layout('layouts.app', ['title' => 'Activities']);
    }

    public function getFormattedDateProperty()
    {
        return Carbon::createFromFormat('M d, Y', $this->date)->format('Y-m-d');
    }

    public function activities()
    {
        ($this->activitiesForUser());
        return Auth::guard('web')->user()->isOwnerOrManager()
            ? $this->activitiesForAccount()
            : $this->activitiesForUser();
    }

    public function activitiesForAccount()
    {
        if ($this->user_id == null) {
            $this->user_id = auth()->id();
        }
        return Activity::query()
            ->whereDate('date', $this->formatted_date)
            ->when($this->user_id, function ($query) {
                return $query->where('user_id', $this->user_id);
            })
            ->oldest('start_datetime')
            ->get()
            ->mapToGroups(function ($activity, $key) {
                return [
                    $activity->start_datetime->format('h:00 a') . ' - ' . $activity->start_datetime->addHour()->format('h:00 a') => $activity
                ];
            });

            
    }

    public function ActivitiesAverage()
    {
        
        $todayActivitySum = User::where('id',$this->user_id)->first()
        ->activities()
        ->whereDate('date', $this->formatted_date)
        ->oldest('start_datetime')
        ->sum('total_activity_percentage');
        
        $todayNumberOfActivities = User::where('id',$this->user_id)->first()
        ->activities()
        ->whereDate('date', $this->formatted_date)
        ->oldest('start_datetime')
        ->count();
        if($todayNumberOfActivities != 0){
            $this->todayActivityPer = intval(round(($todayActivitySum/$todayNumberOfActivities),0));
        }
        else{
            $this->todayActivityPer = 0;
        }
        
        
    }
    public function activitiesForUser()
    {
        $this->ActivitiesAverage();
         return User::where('id',$this->user_id)->first()
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

    public function activitiesTime()
    {
        $isOwner = Auth::guard('web')->user()->isOwnerOrManager();

        $user = null;

        $today = Carbon::createFromFormat('M d, Y', $this->date)->format('Y-m-d');
        $yesterday = Carbon::createFromFormat('M d, Y', $this->date)->subDay()->format('Y-m-d');

        if ($isOwner) {
            if ($this->user_id == null) {
                $this->user_id = auth()->id();
            }
            $user = User::where('id', $this->user_id)->first();
        } else {
            $user = Auth::guard('web')->user();
        }

        if ($user) {
            $this->timeToday = CarbonInterval::seconds($user->activities()->thisPeriodOfTime($today,  $today)->sum('seconds'))->cascade()->format('%H:%I:%S');
            $this->timeToday = strtotime($this->timeToday) - strtotime('TODAY');
            $this->timeYesterday =  CarbonInterval::seconds($user->activities()->thisPeriodOfTime($yesterday, $yesterday)->sum('seconds'))->cascade()->format('%H:%I:%S');
            $this->timeYesterday = strtotime($this->timeYesterday) - strtotime('TODAY');
            $this->totalPreviuosTime = $this->timeToday -  $this->timeYesterday;
        }

        if ($this->totalPreviuosTime > -1) {
            $this->totalPreviuosTimeState = 'more';
        } else {
            $this->totalPreviuosTimeState = 'less';
            $this->totalPreviuosTime = $this->totalPreviuosTime * -1;
        }
    }

    public function create()
    {
        $this->dispatchBrowserEvent('open-activities-form-modal');
    }

    public function delete()
    {
        $this->dispatchBrowserEvent('open-delete-activity-modal');
    }

    public function calculateWeeklyHours(){

        $isOwner = Auth::guard('web')->user()->isOwnerOrManager();

        $user = null;

        if ($isOwner) {
            if ($this->user_id == null) {
                $this->user_id = auth()->id();
            }
            $user = User::where('id', $this->user_id)->first();
        } else {
            $user = Auth::guard('web')->user();
        }

        $week = Carbon::createFromFormat('M d, Y', $this->date)->format('W');
        $year = Carbon::createFromFormat('M d, Y', $this->date)->format('Y');

        $timestamp = mktime( 0, 0, 0, 1, 1,  $year ) + ( $week * 7 * 24 * 60 * 60 );
        $timestamp_for_monday = $timestamp - 86400 * ( date( 'N', $timestamp ) - 1 );
        $date_for_monday = date( 'Y-m-d', $timestamp_for_monday );

        $currentWeekDates = [];
        for($i=0;$i<7;$i++){
            $currentWeekDates[$i] = date('Y-m-d',strtotime($date_for_monday.' + '.$i.' days' ));
        }
        
       
        if($user){
       
        $weekHours = [];
        $WeekActivityPercentage = [];
        foreach($currentWeekDates as $index=>$currentWeekDate){

            $todayActivitySum = User::where('id',$this->user_id)->first()
            ->activities()
            ->whereDate('date', $currentWeekDate)
            ->oldest('start_datetime')
            ->sum('total_activity_percentage');

            $todayNumberOfActivities = User::where('id',$this->user_id)->first()
            ->activities()
            ->whereDate('date', $currentWeekDate)
            ->oldest('start_datetime')
            ->count();
            
            if($todayNumberOfActivities != 0 ){
               
                $WeekActivityPercentage[] = intval(round(($todayActivitySum/$todayNumberOfActivities),0));
                
            }

            $weekHours[$index] = CarbonInterval::seconds($user->activities()->thisPeriodOfTime($currentWeekDate, $currentWeekDate)->sum('seconds'))->cascade()->format('%H:%I:%S');
         }
         $this->weekActivityPer = intval(round((array_sum($WeekActivityPercentage)/7),0));
         $sum = strtotime('00:00:00');

         $totaltime = 0;
         foreach( $weekHours as $element ) {
            $timeinsec = strtotime($element) - $sum;
            $totaltime = $totaltime + $timeinsec;
        }
        $h = intval($totaltime / 3600);
        $totaltime = $totaltime - ($h * 3600);
        $m = intval($totaltime / 60);
        $s = $totaltime - ($m * 60);
        
        if("$h:$m" == "0:0"){
            return ("00:00");
            }
        else
            return ("$h:$m");
        
    }
}
}
