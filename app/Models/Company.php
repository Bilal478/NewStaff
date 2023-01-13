<?php

namespace App\Models;

use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes, BelongsToAccount;
    protected $fillable = [
        'title',
        'user_id',
      ];

    public function departments()
    {
      return $this->belongsToMany(Department::class);
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
    public function addMemeber($departmentId): void
    {
      $this->departments()->attach($departmentId);
    }

    public function removeMemeber($userId): void
    {
      $this->departments()->detach($userId);
    }

    public function hasUser(Department $department): bool
    {
      return $this->departments()->where('departments.id', $department->id)->exists();
    }


}
