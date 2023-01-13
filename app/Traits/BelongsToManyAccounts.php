<?php

namespace App\Traits;

use App\Models\Account;
use App\Scopes\AccountManyScope;

trait BelongsToManyAccounts
{
    protected static function bootBelongsToManyAccounts()
    {
        static::addGlobalScope(new AccountManyScope);
    }

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }
}
