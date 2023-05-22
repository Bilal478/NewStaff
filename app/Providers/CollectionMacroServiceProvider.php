<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionMacroServiceProvider extends ServiceProvider
{
    public $result ;
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
                return $item->firstname . ' ' . $item->lastname;
            });
        });

        Collection::macro('mapActivitiesStatsByDates', function ($dateRange) {
            return $this->map(function ($userActivities) use ($dateRange) {
               
                return [
                    'days' => $dateRange->map(function ($date) use ($userActivities) {
                        $totalSeconds = $userActivities->sum('seconds');
                        $totalHours = floor($totalSeconds / 3600);
                        $remainingSeconds = $totalSeconds % 3600;
                        $this->result = sprintf("%02d:%02d:00", $totalHours, floor($remainingSeconds / 60));
                        $activity = $userActivities->first(function ($activity) use ($date) {
                            return $activity->date->format('Y-m-d') == $date->format('Y-m-d');
                        });

                        return [
                            'seconds' => CarbonInterval::seconds($activity ? $activity->seconds : 0)->cascade()->format('%H:%I:%S'),
                            'date' => $date->format('M d, Y'),
                            'productivity' => $activity ? round($activity->productivity) : 0,
                            'user_id' =>  isset($activity->user_id)  ? $activity->user_id : ''
                        ];
                    }),
                    'total' => $this->result,
                    'total_productivity' => round($userActivities->sum('productivity') / count($userActivities)),

                ];
            });
        });
    }
}
