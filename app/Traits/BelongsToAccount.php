<?php

namespace App\Traits;

use App\Models\Account;
use App\Scopes\AccountScope;

trait BelongsToAccount
{
    protected static function bootBelongsToAccount()
    {
        static::addGlobalScope(new AccountScope);

        static::creating(function ($model) {
            if (session()->has('account_id')) {
                $model->account_id = session()->get('account_id');
            }
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
