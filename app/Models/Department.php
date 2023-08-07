<?php

namespace App\Models;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes, BelongsToAccount;

    protected $fillable = [
      'title',
      'user_id',
      'department_id'
    ];

    public function users()
    {
      return $this->belongsToMany(User::class);
    }
    public function companies()
    {
      return $this->belongsToMany(Company::class);
    }

    public function tasks()
    {
      return $this->hasMany(Task::class);
    }
    public function projects()
    {
      return $this->hasMany(Project::class);
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
