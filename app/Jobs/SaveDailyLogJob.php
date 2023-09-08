<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaveDailyLogJob implements ShouldQueue
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

        $pattern = '/^\[(.*?)\] (.*)$/m'; 
        $matches = [];

        foreach ($errorLogs as $logLine) {
          preg_match($pattern, $logLine, $match);
          if (!empty($match)) {
            $matches[] = $match;
           }
        }
        $desiredDate =  Carbon::now()->subDay()->format('Y-m-d');
        $filteredLogs = [];

        foreach ($matches as $match) {
          $timestamp = $match[1];
          $message = $match[2];
          $logDate = substr($timestamp, 0, 10); // Assuming the timestamp format is consistent
          if ($logDate === $desiredDate) {
            $filteredLogs[] = [
            'timestamp' => $timestamp,
            'message' => $message,
            ];
          }
        }
        $logs_data = DB::table('logs_data')->get();
        foreach ($filteredLogs as $key => $log) {
            $is_compare=false;
            foreach ($logs_data as $data) {
                $databaseMessage = $this->normalizeMessage($data->message);
                $logMessage =$log['message'];
                $logMessage = str_replace(' ', '', $logMessage);
                $logMessage = trim($logMessage);
                if ($databaseMessage === $logMessage && $data->status!='completed') {
                    $errorRecord=DB::table('logs_data')->where('id',$data->id)->first();
                    if($errorRecord){
                        DB::table('logs_data')->where('id',$data->id)->update([
                        'last_date_ocurred' => $log['timestamp'],
                        'times_ocurred' => $data->times_ocurred+1,
                        'updated_at' => now(),
                        ]);
                    }
                    $is_compare=true;
                } 
            }
               if($is_compare==false){
                  DB::table('logs_data')->insert([
                  'timestamp' => $log['timestamp'],
                  'message' => $log['message'],
                  'created_at' => now(),
                  'updated_at' => now(),
                  ]);
                }
        }
    }
    public function normalizeMessage($message)
    {
        $message = preg_replace('/\s+/', '', $message); // Remove all whitespace characters
        return $message;
    }
}
