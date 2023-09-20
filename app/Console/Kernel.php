<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ClearWeeklyLog::class,
        Commands\SendDailyWorkSummary::class,
        Commands\SaveDailyLog::class,
        Commands\PermanentDeleteUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('permanent:deleteusers')
        ->daily();
        $schedule->command('save:dailylog')
        ->daily();
        $schedule->command('clear:weeklylog')
        ->everyMinute();
        $schedule->command('senddaily:worksummary')
        ->daily();
        // ->at('08:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
