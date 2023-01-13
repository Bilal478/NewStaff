<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Screenshot;
use Carbon\CarbonInterval;
use App\Traits\BelongsToAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, SoftDeletes, BelongsToAccount;

    protected $fillable = [
        'from',
        'to',
        'seconds',
        'start_datetime',
        'end_datetime',
        'date',
        'keyboard_count',
        'mouse_count',
        'total_activity',
        'total_activity_percentage',
        'task_id',
        'user_id',
        'project_id',
        'account_id',
    ];

    protected $casts = [
        'from' => 'integer',
        'to' => 'integer',
        'seconds' => 'integer',
        'start_datetime' => 'datetime:Y-m-d H:i:s',
        'end_datetime' => 'datetime:Y-m-d H:i:s',
        'date' => 'date',
        'keyboard_count' => 'integer',
        'mouse_count' => 'integer',
        'total_activity' => 'decimal:0',
        'total_activity_percentage' => 'decimal:0',
    ];

    protected $with = ['screenshots'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($activity) {
            $from = CarbonInterval::seconds($activity->from)->cascade()->format('%H:%I:%S');
            $to = CarbonInterval::seconds($activity->to)->cascade()->format('%H:%I:%S');

            $activity->start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $activity->date->format('Y-m-d') . ' ' . $from)->toDateTimeString();
            $activity->end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $activity->date->format('Y-m-d') . ' ' . $to)->toDateTimeString();
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }

    /**
     * Acessors
     */
    // public function getStartTimeAttribute()
    // {
    //     return $this->start_datetime->format('h:i a');
    // }

    // public function getEndTimeAttribute()
    // {
    //     return $this->end_datetime->format('h:i a');
    // }

    /**
     * Scopes
     */
    public function scopeThisWeek($query, $column = 'date')
    {
        return $query->whereBetween($column, [
            Carbon::now()->startOfWeek(Carbon::MONDAY),
            Carbon::now()->endOfWeek(Carbon::SUNDAY)
        ]);
    }

    public function scopeThisPeriodOfTime($query,$startDay,$endDay)
    {
        $column='date';
        return $query->whereBetween($column, [
            $startDay,
            $endDay
        ]);
    }

    /**
     * Functions
     */
    public function screenshotsFullPath()
    {
        return $this->screenshots->map(function ($screenshot) {
            return $screenshot->fullPath();
        });
    }
}
