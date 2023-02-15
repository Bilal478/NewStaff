<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Screenshot;
use App\Models\AccountInvitation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zipcode',
        'phone',
        'time',
    ];

    protected $casts = [
        'time' => 'integer',
    ];

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['role']);
    }

    public function usersWithRole()
    {
        return $this->belongsToMany(User::class)->withPivot(['id','role','allow_edit_time','allow_delete_screenshot']);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }

    public function invitations()
    {
        return $this->hasMany(AccountInvitation::class);
    }

    /**
     * Functions
     */
    public function addMember(User $user): void
    {
        $this->users()->attach([$user->id => ['role' => 'member']]);
    }

    public function removeMember(User $user): void
    {
        $this->users()->detach([$user->id]);
    }

    public function addOwner(User $user): void
    {
        $this->users()->attach([$user->id => ['role' => 'owner']]);
    }

    public function hasUser(User $user): bool
    {
        return $this->users()->where('users.id', $user->id)->exists();
    }

    /**
     * Static Functions
     */
    public static function getRoleForUser(User $user): string
    {
        return $user->accountsWithRole()
            ->where('account_id', session()->get('account_id'))
            ->first(['role'])->role;
    }
}
