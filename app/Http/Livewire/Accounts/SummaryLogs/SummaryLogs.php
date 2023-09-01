<?php

namespace App\Http\Livewire\Accounts\SummaryLogs;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SummaryLogs extends Component
{
    use WithPagination;
    public function render()
    {
        $summary_logs=DB::table('summary_logs')->paginate(10);
        return view('livewire.accounts.summary-logs.summary-logs', [
            'summary_logs' => $summary_logs,
        ])->layout('layouts.app', ['title' => 'Summary Logs']);
    }
}
