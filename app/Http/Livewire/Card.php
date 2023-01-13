<?php

namespace App\Http\Livewire;

use App\Models\Activity;
use App\Models\Screenshot;
use Livewire\Component;

class Card extends Component
{
    public $activity_id;

    public function render()
    {
        return view('components.activities.card');
    }

}
