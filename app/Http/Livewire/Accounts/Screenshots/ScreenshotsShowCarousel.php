<?php

namespace App\Http\Livewire\Accounts\Screenshots;

use Livewire\Component;
use App\Models\Activity;

class ScreenshotsShowCarousel extends Component
{
    public $date;
    public $user_id;
    public $account_id;
    public $allActivity;
    public $showCarousel = false;
    public $totalScreenshots = 0;
    public $sliderCounter = 0;

    protected $listeners = [
        'screenshotsShow' => 'show',
    ];

    public function show($user_id,$account_id,$date)
    {
            $this->user_id = $user_id;
            $this->account_id = $account_id;
            $this->date = $date;
            $this->allActivity = Activity::with(['project', 'screenshots'])
            ->withCount('screenshots')
            ->where('date', $this->date)
            ->where('user_id', $this->user_id)
            ->where('account_id', $this->account_id)
            ->oldest('start_datetime')
            ->get();
            $this->totalScreenshots = 0;
            foreach($this->allActivity as $index=>$activity){
               $this->totalScreenshots =  $this->totalScreenshots  + $activity->screenshots_count;
            }
			
        $this->showCarousel = true;
    }

    public function render()
    {
        return view('livewire.accounts.screenshots.show-carousel');
    }
}
