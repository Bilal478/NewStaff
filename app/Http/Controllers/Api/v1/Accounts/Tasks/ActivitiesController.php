<?php

namespace App\Http\Controllers\Api\v1\Accounts\Tasks;

use App\Models\Task;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Screenshot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ActivityResource;
use App\Http\Requests\StoreTaskActivityRequest;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;

class ActivitiesController extends Controller
{
    public function store(StoreTaskActivityRequest $request, Account $account, Task $task)
    {
        if (Gate::denies('store-task-activity', $task)) {
            return api_response_unauthorized();
        }

        $from = CarbonInterval::seconds($request->from)->cascade()->format('%H:%I:%S');
        $to = CarbonInterval::seconds($request->to)->cascade()->format('%H:%I:%S');
    
        $date = Carbon::parse($request->date); // Convert $request->date to a Carbon instance
    
        $start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $from)->toDateTimeString();
        $end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $to)->toDateTimeString();
    
        $last_activity_record = Activity::find(Cache::get('last_activity_id'));

        if($last_activity_record){
            if(!($last_activity_record->start_datetime == $start_datetime && $last_activity_record->end_datetime == $end_datetime
            && $last_activity_record->account_id == $account->id &&  $last_activity_record->project_id == $request->project_id &&  $last_activity_record->task_id == $task->id))
            {
                $activity = $task->activities()->create($request->validated());
                Cache::put('last_activity_id', $activity->id);
                $this->storeScreenshots($request, $account, $activity);
    
                return api_response([
                    'message' => 'The activity has been saved.',
                    'activity' => new ActivityResource($activity->refresh())
                ], 200);  
            }
        }
        else{
            $activity = $task->activities()->create($request->validated());
            Cache::put('last_activity_id', $activity->id);
            $this->storeScreenshots($request, $account, $activity);

            return api_response([
                'message' => 'The activity has been saved.',
                'activity' => new ActivityResource($activity->refresh())
            ], 200);  
        }
        
    }

    /**
     * Store screenshots for an activity.
     *
     * @var StoreTaskActivityRequest $request
     * @var Account $account
     * @var Activity $activity
     * @return void
     */
    public function storeScreenshots(StoreTaskActivityRequest $request, Account $account, Activity $activity): void
    {
        collect($request->screenshots)->each(function ($screenshot) use ($request, $account, $activity) {
            $activity->screenshots()->create([
                'path' => Screenshot::saveFile($request->user(), $screenshot),
                'account_id' => $account->id,
            ]);
        });
    }
    
}
