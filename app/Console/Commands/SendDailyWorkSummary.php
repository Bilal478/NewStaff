<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DailyWorkSummaryJob;

class SendDailyWorkSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'senddaily:worksummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily work summary to owner of company';

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
        DailyWorkSummaryJob::dispatch();

        $this->info('Emails sent successfully');
 }

}