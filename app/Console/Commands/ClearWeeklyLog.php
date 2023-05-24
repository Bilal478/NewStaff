<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearWeeklyLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:weeklylog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all logs after a week';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->info('Laravel log file has been cleared!');
    }
}
