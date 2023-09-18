<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Activity;
use App\Models\AccountInvitation;
use App\Models\Department;
use App\Models\Team;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\BelongsToManyAccounts;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable, HasApiTokens, HasFactory, SoftDeletes, Notifiable, BelongsToManyAccounts;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'team',
        'department',
        'punchin_pin_code',
        'punchin_pin_code_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationships
     */


     public function company()
     {
         return $this->hasOne(Company::class);
     }

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function accountsWithRole()
    {
        return $this->belongsToMany(Account::class)->withPivot(['id','role','allow_edit_time','allow_delete_screenshot']);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function accountInvitations() {
	return $this->hasMany(AccountInvitation::class);
    }

    public function departments() {
        return $this->belongsToMany(Department::class);
    }

    public function teams() {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Acessors
     */
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' .$this->lastname;
    }

    /**
     * Functions
     */
    public function isOwner()
    {
        return session()->get('account_role') === 'owner';
    }

    public function isManager()
    {
        return session()->get('account_role') === 'manager';
    }

    public function isOwnerOrManager()
    {
        return in_array(session()->get('account_role'), ['owner', 'manager']);
    }

    public function isNotOwnerOrManager()
    {
	return ! $this->isOwnerOrManager();
    }

    public function belongsToManyAccounts(): bool
    {
        return $this->accounts()->wherePivotNull('deleted_at')->count() > 1;
    }

    /**
     * Scopes
     */
    public function scopeInProject($query, $projectId)
    {
        return $query->whereHas('projects', function ($query) use ($projectId) {
            $query->where('project_id', $projectId)->whereNull('project_user.deleted_at');
        });
    }

    public function scopeNotInProject($query, $projectId)
    {
        return $query->whereDoesntHave('projects', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        });
    }

    public function scopeInDepartment($query, $departmentId)
    {
        return $query->whereHas('departments', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        });
    }

    public function scopeNotInDepartment($query, $departmentId)
    {
        return $query->whereDoesntHave('departments', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        });
    }

    public function scopeInTeam($query, $teamId)
    {
        return $query->whereHas('teams', function ($query) use ($teamId) {
            $query->where('team_id', $teamId);
        });
    }

    public function scopeNotInTeam($query, $teamId)
    {
        return $query->whereDoesntHave('teams', function ($query) use ($teamId) {
            $query->where('team_id', $teamId);
        });
    }

    public function getRole()
    {
        $role = "";
        if ($this->isOwner())
            $role = "owner";
        if ($this->isManager())
            $role = "manager";
        if ($this->isNotOwnerOrManager())
            $role = "member";

        return $role;
    }
}
