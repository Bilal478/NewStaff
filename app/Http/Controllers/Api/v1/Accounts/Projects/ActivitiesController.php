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

class ActivitiesController extends Controller
{
    public function store(StoreProjectActivityRequest $request, Account $account, Project $project)
    {
        if (Gate::denies('store-project-activity', $project)) {
            return api_response_unauthorized();
        }

        $activity = $project->activities()->create($request->validated());

        $this->storeScreenshots($request, $account, $activity);

        return api_response([
            'message' => 'The activity has been saved.',
            'activity' => new ActivityResource($activity->refresh())
        ], 200);
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