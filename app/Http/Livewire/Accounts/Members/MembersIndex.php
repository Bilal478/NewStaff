<?php
namespace App\Http\Livewire\Accounts\Members;


use App\Rules\IsNotMemberOfAccount;
use App\Rules\AccountInvitationUnique;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountInvite;
use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AccountInvitation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Livewire\Traits\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Models\Plans;
use App\Providers\RouteServiceProvider;

class MembersIndex extends Component
{
    use WithPagination, Notifications;

    public $role = 'member';
    public $email = '';
    public $search = '';
    public $url='';
    
    public $filter = 'members';

    protected $listeners = [
        'membersUpdate' => '$refresh',
        'memberInvitationSend' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function editTimePermssion($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;

        DB::table('account_user')
            ->where('id', $id)
            ->update(['allow_edit_time' => $permission]);
    }

    public function editScreenshotPermssion($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;
        
        DB::table('account_user')
            ->where('id', $id)
            ->update(['allow_delete_screenshot' => $permission]);
    }

    public function editTimePermssionInvite($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;

        DB::table('account_invitations')
            ->where('id', $id)
            ->update(['allow_edit_time' => $permission]);
    }

    public function editScreenshotPermssionInvite($id,$permission){
        
        ($permission == 1)?$permission = 0: $permission=1;
        
        DB::table('account_invitations')
            ->where('id', $id)
            ->update(['allow_delete_screenshot' => $permission]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function memberDelete(User $user)
    {
        if (Gate::denies('delete-account-member', $this->account)) {
            return $this->toast(
                'Unauthorize Action',
                'You need at least one owner on an account.',
                'error'
            );
        }
        $loggedUser=Auth::user();
        if($loggedUser->id==$user->id){
            $this->toast('Permission Denied', "Owner can not be deleted.");
        }
        else{
        $user->activities()->delete();
        $user->tasks()->where('account_id', $this->account->id)->update(['user_id' => null]);
        // $user->projects()->syncWithoutDetaching([$user->id => ['deleted_at' => now()]]);
        DB::table('project_user')
        ->join('projects', 'project_user.project_id', '=', 'projects.id')
        ->where('project_user.user_id', $user->id)
        ->where('projects.account_id', $this->account->id)
        ->update(['project_user.deleted_at' => now()]);
        // $user->departments()->syncWithoutDetaching([$user->id => ['deleted_at' => now()]]);
        DB::table('department_user')
        ->join('departments', 'department_user.department_id', '=', 'departments.id')
        ->where('department_user.user_id', $user->id)
        ->where('departments.account_id', $this->account->id)
        ->update(['department_user.deleted_at' => now()]);

        // $user->projects()->detach();
        // $user->departments()->detach();
        if ($user->belongsToManyAccounts()) {
            $this->account->users()->syncWithoutDetaching([$user->id => ['deleted_at' => now()]]);
            // DB::table('account_user')->where('user_id', $user->id)->update(['deleted_at' => now()]);
        } 
        else {
            $this->account->removeMember($user);
            $user->delete();
        }
        $invitationRecord=AccountInvitation::where('email',$user->email)
        ->where('account_id',$this->account->id)->first();
        if($invitationRecord){
            $invitationRecord->delete();
        }
        $this->toast("User is moved to trash");
       
    }
    }

    public function memberRestore($userId)
    {
        DB::table('project_user')
        ->join('projects', 'project_user.project_id', '=', 'projects.id')
        ->where('project_user.user_id', $userId)
        ->where('projects.account_id', $this->account->id)
        ->update(['project_user.deleted_at' => NULL]);
        DB::table('department_user')
        ->join('departments', 'department_user.department_id', '=', 'departments.id')
        ->where('department_user.user_id', $userId)
        ->where('departments.account_id', $this->account->id)
        ->update(['department_user.deleted_at' => NULL]);
        DB::table('account_user')
        ->where('user_id', $userId)
        ->where('account_id',  $this->account->id)
        ->update(['deleted_at' => NULL]);
        DB::table('users')->where('id', $userId)->update(['deleted_at' => NULL]);
        $this->toast("User is restored");
    }
    public function memberPermanentDelete($userId)
    {
        $user=Auth::user();
        $subscription=DB::table('subscriptions')->where('user_id',$user->id)->first();
        DB::table('project_user')
        ->join('projects', 'project_user.project_id', '=', 'projects.id')
        ->where('project_user.user_id', $userId)
        ->where('projects.account_id', $this->account->id)
        ->delete();
        DB::table('department_user')
        ->join('departments', 'department_user.department_id', '=', 'departments.id')
        ->where('department_user.user_id', $userId)
        ->where('departments.account_id', $this->account->id)
        ->delete();
        $totalUser= DB::table('account_user')->where('user_id', $userId)->get();

        if (count($totalUser)>1) {
            $this->account->users()->detach($userId);
        } 
        else {
            $this->account->users()->detach($userId);
            DB::table('users')->where('id', $userId)->delete();
        }
        DB::table('transaction_log')->insert([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'action' => 'cancel_subscription',
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->toast("User is deleted permanently");
    }

    public function copyInvitation(AccountInvitation $accountInvitation)
    {
        $invitation_link = new AccountInvite($this->account, $accountInvitation);
        
        $array = (array) $invitation_link;
        $this->url = $array['url'];
       // $this->toast('Invtation link', "The invitation link  is".$array['url']);

    }

    public function Resend(AccountInvitation $accountInvitation)
    {
        Mail::to($accountInvitation->email)->send(new AccountInvite($this->account, $accountInvitation));

        $this->emit('memberInvitationSend');
        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Send', "The invitation to {$this->email} has been sent.");
        $this->reset(['email', 'role']);

    }
    public function inviteDelete(AccountInvitation $accountInvitation)
    {
        $accountInvitation->delete();
    }

    public function getAccountProperty()
    {
        return Account::find(session()->get('account_id'));
    }

    public function render()
    {
		return view('livewire.accounts.members.index', [
			'users' => $this->users(), 'url'=> $this->url,
		])->layout('layouts.app', ['title' => 'Members']);
    }

    public function users()
    {
        // return $this->filter == 'members'
        //     ? $this->members()
        //     : $this->invites();
        if ($this->filter == 'members') {
            return $this->members();
        } elseif ($this->filter == 'invites') {
            return $this->invites();
        } else {
            return $this->trashedUsers();
        }
    }
	
	public function create2()
    {
        $this->dispatchBrowserEvent('close-invite-modal');
        $this->toast('Invitation Sent', "The invitation to has been sent.");  
    }
	
    public function members()
    {
        return $this->account
            ->usersWithRole()
            ->whereNull('account_user.deleted_at')
            ->where('invitation_accept', '!=', 'true')
            ->latest()
            ->where(function (Builder $query) {
                return $query->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
            })
            ->paginate(8);
    }

    public function invites()
    {
        return AccountInvitation::where('invitation_accept', '!=', 'true')->latest()
            ->where('email', 'like', '%' . $this->search . '%')
            ->paginate(8);
    }

    public function trashedUsers()
    {
        return $this->account
            ->usersWithRole()
            ->withTrashed()
            ->whereNotNull('account_user.deleted_at')
            ->where('invitation_accept', '!=', 'true')
            ->latest()
            ->where(function (Builder $query) {
                return $query->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
            })
            ->paginate(8);
    }
	
	public function payandcontinue(Request $request){
		
		$user = Auth::user();
        $subscription=$user->subscription($request->plan);
		
		if ($user->hasDefaultPaymentMethod()) {
			
			$user->subscription($request->plan)
				->incrementQuantity($request->selectseats);	
                DB::table('transaction_log')->insert([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id,
                    'action' => 'buy_seats',
                    'quantity' => $request->selectseats,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
		}
		
		return redirect()->intended("/members?buy_more_seats="."$request->selectseats");	
	}
}