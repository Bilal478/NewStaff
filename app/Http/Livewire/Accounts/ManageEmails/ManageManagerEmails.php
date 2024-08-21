<?php

namespace App\Http\Livewire\Accounts\ManageEmails;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageManagerEmails extends Component
{
    use WithPagination;
    public $account_id;

    protected $listeners = [
        'tasksUpdate' => '$refresh',
    ];

    public function mount()
    {
        $this->account_id = session()->get('account_id');
    }
    public function getAccountProperty()
    {
        return Account::find($this->account_id);
    }
    public function editTimePermssion($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;

        DB::table('account_user')
            ->where('id', $id)
            ->update(['email_status' => $permission]);
    }
    public function editCustomEmailPermssion($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;

        DB::table('manage_custom_emails')
            ->where('id', $id)
            ->update(['email_status' => $permission]);
    }
    public function render()
    {
        $managers = DB::table('account_user')
        ->join('users', 'account_user.user_id', '=', 'users.id')
        ->where(['account_user.account_id' => $this->account_id, 'account_user.role' => 'manager'])
        ->select('account_user.*', 'users.firstname', 'users.lastname', 'users.email')
        ->get();
        $customUsers=DB::table('manage_custom_emails')->get();
        return view('livewire.accounts.manage-emails.manage-manager-emails', [
            'managers' => $managers,
            'customUsers' => $customUsers,
        ])->layout('layouts.app', ['title' => 'Manage Emails']);
    }
}
