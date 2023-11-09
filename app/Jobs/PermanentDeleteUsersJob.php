<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PermanentDeleteUsersJob implements ShouldQueue
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
        $thresholdDate = now()->subDays(30); 
        $accountUsers=DB::table('account_user')
        ->whereNotNull('deleted_at')
        ->where('deleted_at', '<', $thresholdDate)
        ->get();
        if($accountUsers){
          foreach($accountUsers as $user){
                $subscription=DB::table('subscriptions')->where('user_id',$user->deleted_by_user_id)->first();
                 DB::table('transaction_log')->insert([
                'user_id' =>  $user->deleted_by_user_id,
                'account_id' => $user->deleted_by_account_id,
                'subscription_id' => $subscription->id,
                'action' => 'cancel_subscription',
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
            }
        }
        DB::table('project_user')
        ->whereNotNull('deleted_at')
        ->where('deleted_at', '<', $thresholdDate)
        ->delete();
        DB::table('department_user')
        ->whereNotNull('deleted_at')
        ->where('deleted_at', '<', $thresholdDate)
        ->delete();
        DB::table('account_user')
        ->whereNotNull('deleted_at')
        ->where('deleted_at', '<', $thresholdDate)
        ->delete();
        DB::table('users')
        ->whereNotNull('deleted_at')
        ->where('deleted_at', '<', $thresholdDate)
        ->delete();
    }
}
