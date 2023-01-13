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

    protected $listeners = [
        'screenshotsShow' => 'show',
    ];

    public function show($user_id,$account_id,$date)
    {
        // dd($user_id);
        // // foreach ($this->allActivity as $key => $value) {
            // //     dump($value->screenshots);
            // // }

            $this->user_id = $user_id;
            $this->account_id = $account_id;
            $this->date = $date;
            $this->allActivity = Activity::with('screenshots')->where('date',$date)->where('user_id',$user_id)->where('account_id',$account_id)->orderBy('start_datetime')->get();
            //dd($this->allActivity);
			
        $this->showCarousel = true;
    }

    public function render()
    {
        return view('livewire.accounts.screenshots.show-carousel');
    }
}
