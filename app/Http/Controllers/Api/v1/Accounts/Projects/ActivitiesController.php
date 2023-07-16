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
        && $last_activity_record->account_id == $account->id &&  $last_activity_record->project_id == $project->id))
        {
            $activity = $project->activities()->create($request->validated());
            Cache::put('last_activity_id', $activity->id);
            $this->storeScreenshots($request, $account, $activity);

            return api_response([
                'message' => 'The activity has been saved.',
                'activity' => new ActivityResource($activity->refresh())
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