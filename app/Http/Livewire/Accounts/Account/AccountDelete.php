<?php

namespace App\Http\Livewire\Accounts\Account;

use App\Models\Account;
use Livewire\Component;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountDelete extends Component
{
    public function delete()
    {
        $account = Account::find(session()->get('account_id'));
        $account->screenshots->each(function($screenshot) {
            Storage::disk(Screenshot::STORAGE_DISK)->delete($screenshot->path);
            $screenshot->delete();
        });
        $account->screenshots()->delete();

        $account->activities()->forceDelete();

        $account->tasks()->forceDelete();

        $account->projects->each(function($project) {
            $project->users()->detach();
            $project->forceDelete();
        });

        $account->invitations()->delete();

        $account->users->each(function ($user) use ($account) {
            if ($user->accounts->count() == 1) {
                $account->users()->detach($user->id);
                $user->forceDelete();
            } else {
                $account->users()->detach($user->id);
            }
        });

        $account->delete();

        Auth::guard('web')->logout();

        return redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.accounts.settings.delete');
    }
}
