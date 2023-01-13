<?php

namespace App\Rules;

use App\Models\AccountInvitation;
use Illuminate\Contracts\Validation\Rule;

class AccountInvitationUnique implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return AccountInvitation::query()
            ->where('email', $value)
            ->where('account_id', session()->get('account_id'))
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email already have an invitation for this account.';
    }
}
