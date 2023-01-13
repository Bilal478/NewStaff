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
        ])->layout('layouts.app', ['title' => 'Activities']);
    }

    public function getFormattedDateProperty()
    {
        return Carbon::createFromFormat('M d, Y', $this->date)->format('Y-m-d');
    }

    public function activities()
    {
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
}
