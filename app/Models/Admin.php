<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Project;
use App\Models\Screenshot;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Acessors
     */
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' .$this->lastname;
    }
}
