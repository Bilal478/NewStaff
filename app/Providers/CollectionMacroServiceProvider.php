<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionMacroServiceProvider extends ServiceProvider
{
    public $result = 0;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('groupByUserName', function () {
            return $this->groupBy(function ($item) {
                return $item->project_title;
            });
        });
        Collection::macro('groupByUserNames', function () {
            return $this->groupBy(function ($item) {
                return $item->task_title;
            });
        });

        Collection::macro('mapActivitiesStatsByDates', function ($dateRange) {
            return $this->map(function ($userActivities) use ($dateRange) {
                // dd($userActivities);
                    $days = $dateRange->map(function ($date) use ($userActivities) {
                        
                        $activity = $userActivities->first(function ($activity) use ($date) {
                            // dd($activity);
                            return $activity->date->format('Y-m-d') == $date->format('Y-m-d');
                        });

                        return [
                            'seconds2' => $activity ? $activity->seconds : 0,
                            'seconds' => CarbonInterval::seconds($activity ? $activity->seconds : 0)->cascade()->format('%H:%I:%S'),
                            'date' => $date->format('M d, Y'),
                            'productivity' => $activity ? round($activity->productivity) : 0,
                            'user_id' =>  isset($activity->user_id)  ? $activity->user_id : '',
                            'project_title' =>  isset($activity->project_id)  ? $activity->project_title : '',
                            'task_title' =>  isset($activity->task_id)  ? $activity->task_title : 'No to-do',
                        ];
                    });
                    $totalSeconds = $days->sum(function ($day) {
                        return $day['seconds2'];
                    });
                    $totalHours = floor($totalSeconds / 3600);
                    $remainingSeconds = $totalSeconds % 3600;
                   
                    return [
                    'days' => $days,
                    'task_title' => $userActivities[0]->task_title,
                    'user_name' => $userActivities[0]->full_name,
                    'total' => sprintf("%02d:%02d:00", $totalHours, floor($remainingSeconds / 60)),
                    'total_productivity' => round($userActivities->sum('productivity') / count($userActivities)),

                ];
            });
        });
        
    }
}
