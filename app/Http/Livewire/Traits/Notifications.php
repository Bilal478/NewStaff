<?php

namespace App\Http\Livewire\Traits;

trait Notifications
{
    public function toast($title = '', $description = '', $type = 'success', $duration = 2500)
    {
        $this->dispatchBrowserEvent('toast', [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'duration' => $duration,
        ]);
    }
}
