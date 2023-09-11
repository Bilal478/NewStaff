<?php

namespace App\Console\Commands;

use App\Jobs\SaveDailyLogJob;
use Illuminate\Console\Command;

class SaveDailyLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:dailylog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save error logs daily';

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
        SaveDailyLogJob::dispatch();
        $this->info('Errors are saved to database');
    }
   
}
