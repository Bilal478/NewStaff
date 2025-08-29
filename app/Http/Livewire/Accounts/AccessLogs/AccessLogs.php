<?php

namespace App\Http\Livewire\Accounts\AccessLogs;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AccessLogs extends Component
{
    use WithPagination;
    
    public function render()
    {
        return view('livewire.accounts.access-logs.access-logs', [
				'accessLogs' => $this->accessLogsData(),
				])->layout('layouts.app', ['title' => 'Access Logs']);
    }

    public function accessLogsData()
{
    $data = DB::table('access_logs as al')
        ->leftJoin('users as u1', 'al.user_id', '=', 'u1.id')          // actor
        ->leftJoin('users as u2', 'al.target_user_id', '=', 'u2.id')   // target
        ->select(
            'al.*',
            DB::raw("CONCAT(u1.firstname, ' ', u1.lastname) as performed_by_name"),
            'u1.email as performed_by_email',
            DB::raw("CONCAT(u2.firstname, ' ', u2.lastname) as target_user_name"),
            'u2.email as target_user_email'
        )
        ->orderByDesc('al.created_at')
        ->paginate(8);

    return $data;
}


}
