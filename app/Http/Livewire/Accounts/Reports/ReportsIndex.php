<?php

namespace App\Http\Livewire\Accounts\Reports;

use App\Models\Subscription;



use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
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
    public $week;
    public $search = '';
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
	// $this->user_list = User::orderBy('firstname')->get(['id', 'firstname', 'lastname']);
	#this->user_id = $this->users->first()->id;
            $user_login = auth()->id();
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
			return view('livewire.accounts.reports.index', [
				'users' => $this->getUsersReport(),
				'dates' => $this->getWeekDates(),
			])->layout('layouts.app', ['title' => 'Reports']);
    }

    public function getUsersReport()
    {
        return Activity::join('users', 'activities.user_id', '=', 'users.id')
            ->groupBy('user_id', 'date','firstname','lastname')
            ->selectRaw('sum(seconds) as seconds, avg(total_activity_percentage) as productivity, date, firstname, lastname, user_id')
            ->whereBetween('date', [$this->startDate(true), $this->endDate(true)])
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
}
