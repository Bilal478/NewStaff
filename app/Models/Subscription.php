<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'stripe_status',
    ];
    use HasFactory;
    public function user()
   {
    return $this->belongsTo(User::class);
   }
}
