<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use App\Models\Activity;
use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes, BelongsToAccount;

    protected $fillable = [
        'title',
        'description',
        'label',
        'category',
        'time_expense_tracking',
        'company_id',
        'user_id',
        'account_id',
        'department_id'
    ];

    protected $casts = [
        'time_expense_tracking' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Scoped queries
     */
    public function scopeTitleSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%');
    }

    /**
     * Methods
     */
    public function addMemeber($userId): void
    {
        $this->users()->attach($userId);
    }

    public function removeMemeber($userId): void
    {
        $this->users()->detach($userId);
    }

    public function hasUser(User $user): bool
    {
        return $this->users()->where('users.id', $user->id)->exists();
    }
}
