<?php

namespace App\Console\Commands;

use App\Jobs\PermanentDeleteUsersJob;
use Illuminate\Console\Command;

class PermanentDeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permanent:deleteusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'After 30 days users are permenently deleted from trash';

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

        PermanentDeleteUsersJob::dispatch();
        $this->info('Users are permanently deleted from trash');
    }
}
