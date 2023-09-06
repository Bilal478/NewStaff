<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ClearWeeklyLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logPath);
        $errorLogs = [];
        $logLines = explode("\n", $logContents);

        foreach ($logLines as $line) {
          if (strpos($line, 'ERROR') !== false) {
            $errorLogs[] = $line;
           }
        }

        $pattern = '/^\[(.*?)\] (.*)$/m'; // Notice the 'm' modifier
        $matches = [];

        foreach ($errorLogs as $logLine) {
          preg_match($pattern, $logLine, $match);
          if (!empty($match)) {
            $matches[] = $match;
           }
        }

        foreach ($matches as $match) {
          $timestamp = $match[1];
          $message = $match[2];
    
          DB::table('logs_data')->insert([
          'timestamp' => $timestamp,
          'message' => $message,
          'created_at' => now(),
          'updated_at' => now(),
           ]);
        }
        file_put_contents(storage_path('logs/laravel.log'), '');
    }
}
