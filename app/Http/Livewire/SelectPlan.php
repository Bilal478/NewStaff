<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SelectPlan extends Component
{
    public function index()
    {
        return view('livewire.select-plan')->layout('layouts.auth2', ['title' => 'Plan Selection']);;
    }
}