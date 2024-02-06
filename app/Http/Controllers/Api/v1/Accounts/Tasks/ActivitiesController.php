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
    public function store1(StoreTaskActivityRequest $request, Account $account, Task $task)
{
     if (Gate::denies('store-task-activity', $task)) {
        return api_response_unauthorized();
    }
    $from = CarbonInterval::seconds($request->from)->cascade()->format('%H:%I:%S');
    $to = CarbonInterval::seconds($request->to)->cascade()->format('%H:%I:%S');
    $date = Carbon::parse($request->date);
    $findFrom = floor($request->from / 600) * 600;
    $findTo = ceil($request->to / 600) * 600;
    
    // Convert $findFrom and $findTo to the same format as $start_datetime and $end_datetime
    $findFrom = Carbon::parse($date->format('Y-m-d') . ' ' . gmdate('H:i:s', $findFrom))->toDateTimeString();
    $findTo = Carbon::parse($date->format('Y-m-d') . ' ' . gmdate('H:i:s', $findTo))->toDateTimeString();
    
    $start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $from)->toDateTimeString();
    $end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $to)->toDateTimeString(); 

    // Find record in the 10-minute slot
    $existingActivity = $task->activities()
    ->whereBetween('start_datetime', [$findFrom,$findTo])
    ->whereBetween('end_datetime', [$findFrom,$findTo])
    ->first();
        if ($existingActivity) {
        // Update the existing record
        $secondsToAdd = $request->to - $request->from;
        $existingActivity->update([
            // Update other fields as needed
            'to' => $request->to,
            'seconds' => $existingActivity->seconds+$secondsToAdd,
            'end_datetime' => $request->end_datetime ?: $end_datetime,
        ]);
        $this->storeScreenshots($request, $account, $existingActivity);

        return api_response([
            'message' => 'The activity has been updated.',
            'activity' => new ActivityResource($existingActivity->refresh())
        ], 200);
        } else {
        // Create a new record
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
