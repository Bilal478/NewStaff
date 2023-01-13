<?php

namespace App\Rules;

use App\Models\Account;
use Illuminate\Contracts\Validation\Rule;

class AtLeastOneOwner implements Rule
{
    private $account;
    private $currentRole;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currentRole)
    {
        $this->account = Account::find(session()->get('account_id'));
        $this->currentRole = $currentRole;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == 'owner' || $this->currentRole != 'owner') {
            return true;
        }

        $ownersCount = $this->account
            ->usersWithRole()
            ->where('role', 'owner')
            ->count();

        return $ownersCount == 1 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There should be at least one owner for the account.';
    }
}
