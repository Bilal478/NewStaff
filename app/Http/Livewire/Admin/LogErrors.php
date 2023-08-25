<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class LogErrors extends Component
{
    use WithPagination;
    public function render()
    {
        $all=DB::table('logs_data')->paginate(10);
        return view('livewire.admin.log-errors', [
            'logsData' => $all,
        ])->layout('layouts.admin', ['title' => 'Log Errors']);
    }
}
