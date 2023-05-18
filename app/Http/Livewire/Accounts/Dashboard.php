<?php

namespace App\Http\Livewire\Accounts;

use App\Models\Subscription;
use App\Models\User;

use App\Models\Account;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Activity;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $timeCount;
    public $projectsCount;
    public $tasksCount;
    public $activitiesCount;
    public $projects;
    public $tasks;
    public $week;
    public $dashWeek;

    public function mount()
    {
        Auth::guard('web')->user()->isOwnerOrManager()
            ? $this->dashboardForAccount()
            : $this->dashboardForUser();

        $this->week = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('M d, Y')  . '  -  ' .  Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('M d, Y');
        $this->dashWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('M d')  . '  to  ' .  Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('M d');
    }

    public function dashboardForAccount()
    {
        
        $totalSeconds = Auth::guard('web')->user()->activities()->thisWeek()->sum('seconds');
        $totalHours = floor($totalSeconds / 3600);
        $remainingSeconds = $totalSeconds % 3600;
        //dd(CarbonInterval::seconds(Activity::thisWeek()->sum('seconds'))->cascade()->format('%H:%I:%S'));
     //   dd(CarbonInterval::seconds(Auth::guard('web')->user()->activities()->thisWeek()->sum('seconds'))->cascade()->format('%H:%I:%S'));
        // $this->timeCount = CarbonInterval::seconds(Activity::thisWeek()->sum('seconds'))->cascade()->format('%H:%I:%S');
        $this->timeCount = sprintf("%02d:%02d:00", $totalHours, floor($remainingSeconds / 60));
        $this->projectsCount = Activity::thisWeek()->select('project_id')->distinct()->get()->count();
        $this->tasksCount = Activity::thisWeek()->select('task_id')->distinct()->get()->count();
        $this->activitiesCount = Activity::thisWeek()->get()->count();
        $this->projects = Activity::groupBy('project_id')->with('project:id,title')->selectRaw('sum(seconds) as seconds, project_id')->thisWeek()->take(3)->get();
        $this->tasks = Activity::groupBy('task_id')->with('task:id,title')->selectRaw('sum(seconds) as seconds, task_id')->thisWeek()->take(3)->get();
    }

    public function dashboardForUser()
    {
   
        $totalSeconds = Auth::guard('web')->user()->activities()->thisWeek()->sum('seconds');
        $totalHours = floor($totalSeconds / 3600);
        $remainingSeconds = $totalSeconds % 3600;
        $this->timeCount = sprintf("%02d:%02d:00", $totalHours, floor($remainingSeconds / 60));
        // $this->timeCount = CarbonInterval::seconds(Auth::guard('web')->user()->activities()->thisWeek()->sum('seconds'))->cascade()->format('%H:%I:%S');
        $this->projectsCount = Auth::guard('web')->user()->activities()->thisWeek()->select('project_id')->distinct()->get()->count();
        $this->tasksCount = Auth::guard('web')->user()->activities()->thisWeek()->select('task_id')->distinct()->get()->count();
        $this->activitiesCount = Auth::guard('web')->user()->activities()->thisWeek()->get()->count();
        $this->projects = Auth::guard('web')->user()->activities()->groupBy('project_id')->with('project:id,title')->selectRaw('sum(seconds) as seconds, project_id')->thisWeek()->take(3)->get();
        $this->tasks = Auth::guard('web')->user()->activities()->groupBy('task_id')->with('task:id,title')->selectRaw('sum(seconds) as seconds, task_id')->thisWeek()->take(3)->get();
    }

    public function render()
    {
		return view('livewire.accounts.dashboard')
         ->layout('layouts.app', ['title' => 'Dashboard']);
		
    }
}
