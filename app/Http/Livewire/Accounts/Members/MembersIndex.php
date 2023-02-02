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

        $user->activities()->delete();
        $user->tasks()->update(['user_id' => null]);
        $user->projects()->detach();

        if ($user->belongsToManyAccounts()) {
            $this->account->users()->syncWithoutDetaching([$user->id => ['role' => 'removed']]);
        } else {
            $this->account->removeMember($user);
            $user->forceDelete();
        }
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
        return $this->filter == 'members'
            ? $this->members()
            : $this->invites();
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
            ->where('role', '!=', 'removed')
            ->latest()
            ->where(function (Builder $query) {
                return $query->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
            })
            ->paginate(8);
    }

    public function invites()
    {
        return AccountInvitation::latest()
            ->where('email', 'like', '%' . $this->search . '%')
            ->paginate(8);
    }
	
	public function payandcontinue(Request $request){
		
		$user = Auth::user();
		
		if ($user->hasDefaultPaymentMethod()) {
			
			
			$user->subscription($request->plan)
				->incrementQuantity($request->selectseats);	
		}
		
		return redirect()->intended("/members?buy_more_seats="."$request->selectseats");	
	}
}