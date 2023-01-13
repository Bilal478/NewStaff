<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use App\Models\Activity;
use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes, BelongsToAccount;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'label',
        'time_estimation',
        'hidden_clients',
        'high_priority',
        'default',
        'completed',
        'user_id',
        'creator_id',
        'updater_id',
        'project_id',
        'account_id',
        'team_id',
        'department_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'hidden_clients' => 'boolean',
        'high_priority' => 'boolean',
        'time_estimation' => 'decimal:0',
        'default' => 'boolean',
        'completed' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function assignUser(User $user): void
    {
        $this->update(['user_id' => $user->id]);
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function isAssignedToUser(User $user): bool
    {
        return $this->user_id == $user->id;
    }

    public function complete(): bool
    {
        return $this->update([
            'completed' => true
        ]);
    }
}
