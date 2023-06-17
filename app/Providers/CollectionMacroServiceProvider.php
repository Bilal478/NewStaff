<?php

namespace App\Providers;

use App\Models\Task;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionMacroServiceProvider extends ServiceProvider
{
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

        Collection::macro('mapActivitiesStatsByDates', function ($dateRange) {
            return $this->map(function ($userActivities) use ($dateRange) {
                return [
                    'days' => $dateRange->map(function ($date) use ($userActivities) {
                        $activity = $userActivities->first(function ($activity) use ($date) {
                            return $activity->date->format('Y-m-d') == $date->format('Y-m-d');
                        });

                        return [
                            'seconds' => CarbonInterval::seconds($activity ? $activity->seconds : 0)->cascade()->format('%H:%I:%S'),
                            'date' => $date->format('M d, Y'),
                            'productivity' => $activity ? round($activity->productivity) : 0,
                            'user_id' =>  isset($activity->user_id)  ? $activity->user_id : '',
                            'project_title' =>  $userActivities[0]->project_title,
                            'task_title' =>  $userActivities[0]->task_title
                        ];
                    }),
                    'task_title' => $userActivities[0]->task_title,
                    'total' => CarbonInterval::seconds($userActivities->sum('seconds'))->cascade()->format('%H:%I:%S'),
                    'total_productivity' => round($userActivities->sum('productivity') / 7),

                ];
            });
        });
    }
}
