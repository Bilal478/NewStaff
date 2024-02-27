<?php

namespace App\Http\Controllers\Api\v1\Accounts\Projects;

use App\Models\Account;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Screenshot;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ActivityResource;
use App\Http\Requests\StoreProjectActivityRequest;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;

class ActivitiesController extends Controller
{
    public function store(StoreProjectActivityRequest $request, Account $account, Project $project)
    {
        if (Gate::denies('store-project-activity', $project)) {
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
        && $last_activity_record->account_id == $account->id &&  $last_activity_record->project_id == $project->id && $last_activity_record->task_id == $request->task_id))
        {
            $activity = $project->activities()->create($request->validated());
            Cache::put('last_activity_id', $activity->id);
            $this->storeScreenshots($request, $account, $activity);

            return api_response([
                'message' => 'The activity has been saved.',
                'activity' => new ActivityResource($activity->refresh())
            ], 200);
        }
        else{
            $last_activity_record->update([
                // Update other fields as needed
                // 'to' => $request->from+600,
                'seconds' => $request->seconds,
                // 'end_datetime' => $request->end_datetime ?: $findTo,
            ]);
            Cache::put('last_activity_id', $last_activity_record->id);
            $this->storeScreenshots($request, $account, $last_activity_record);
            return api_response([
                'message' => 'The activity has been updated.',
                'activity' => new ActivityResource($last_activity_record->refresh())
            ], 200);

        }
       }
       else{
            $activity = $project->activities()->create($request->validated());
            Cache::put('last_activity_id', $activity->id);
            $this->storeScreenshots($request, $account, $activity);

            return api_response([
                'message' => 'The activity has been saved.',
                'activity' => new ActivityResource($activity->refresh())
            ], 200);
       }
        
      
    }
    public function store1(StoreProjectActivityRequest $request, Account $account, Project $project)
    {
        if (Gate::denies('store-project-activity', $project)) {
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
        $existingActivity = $project->activities()
        ->whereBetween('start_datetime', [$findFrom,$findTo])
        ->whereBetween('end_datetime', [$findFrom,$findTo])
        ->first();
            if ($existingActivity) {
            // Update the existing record
            $secondsToAdd = $request->to - $request->from;
            $existingActivity->update([
                // Update other fields as needed
                'to' => $request->from+600,
                'seconds' => $existingActivity->seconds+$secondsToAdd,
                'end_datetime' => $request->end_datetime ?: $findTo,
            ]);
            Cache::put('last_activity_id', $existingActivity->id);
            $this->storeScreenshots($request, $account, $existingActivity);
    
            return api_response([
                'message' => 'The activity has been updated.',
                'activity' => new ActivityResource($existingActivity->refresh())
            ], 200);
            } else {
            // Create a new record
            // $activity = $project->activities()->create($request->validated());
            $requestArray = $request->validated();
            $requestArray['to'] = $request->from + 600; // Modify 'to' based on your requirement
            $activity = $project->activities()->create($requestArray);
            Cache::put('last_activity_id', $activity->id);
            $this->storeScreenshots($request, $account, $activity);
    
            return api_response([
                'message' => 'The activity has been saved.',
                'activity' => new ActivityResource($activity->refresh())
            ], 200);
        }
    }

    public function storeScreenshots(StoreProjectActivityRequest $request, Account $account, Activity $activity): void
    {
        collect($request->screenshots)->each(function ($screenshot) use ($request, $account, $activity) {
            $activity->screenshots()->create([
                'path' => Screenshot::saveFile($request->user(), $screenshot),
                'account_id' => $account->id,
            ]);
        });
    }
}