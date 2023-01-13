<?php

namespace App\Http\Livewire\Accounts\Account;

use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Livewire\Traits\Notifications;

class AccountInfo extends Component
{
    use Notifications;

    protected $listeners = [
        'accountUpdated' => '$refresh',
        'accountCreated' => 'changeAccount',
    ];

    public function changeAccount(Account $account)
    {
        if (!$account->hasUser(Auth::guard('web')->user())) {
            return $this->toast(
                'Unauthorize Action',
                'You don\'t have permission to access the account.',
                'error',
                4000
            );
        }

        $changeAccount = Auth::guard('web')->user()
            ->accountsWithRole()
            ->where('account_id', $account->id)
            ->first();

        session()->put('account_id', $changeAccount->id);
        session()->put('account_role', $changeAccount->pivot->role);

        return redirect()->intended(route(RouteServiceProvider::HOME));
    }

    public function render()
    {
        return view('livewire.accounts.account.info', [
            'currentAccount' => Account::find(session()->get('account_id')),
            'accounts' => Auth::guard('web')->user()->accounts()->get(['accounts.id', 'accounts.name']),
        ]);
    }
}
