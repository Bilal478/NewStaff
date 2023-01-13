<?php

namespace Tests\Setup;

use App\Models\Account;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AccountFactory
{
    /**
     * Create an account with a user that belongs to that account.
     *
     * @param  string $role
     * @return array
     */
    public static function accountWithUser($role = 'member')
    {
        $user = User::factory()->create();
        $account = Account::factory()->hasAttached($user, ['role' => $role])->create();

        return [$account, $user];
    }

    /**
     * Create an account with a user that belongs to that account and is logged
     * in with Sanctum.
     *
     * @param  string $role
     * @return array
     */
    public static function accountWithSanctumUser($role = 'member')
    {
        $user = User::factory()->create();
        $account = Account::factory()->hasAttached($user, ['role' => $role])->create();

        Sanctum::actingAs($user, ['*']);

        return [$account, $user];
    }
}
