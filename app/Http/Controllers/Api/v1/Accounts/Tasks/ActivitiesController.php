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

class ActivitiesController extends Controller
{
    public function store(StoreTaskActivityRequest $request, Account $account, Task $task)
    {
        if (Gate::denies('store-task-activity', $task)) {
            return api_response_unauthorized();
        }

        $activity = $task->activities()->create($request->validated());

        $this->storeScreenshots($request, $account, $activity);

        return api_response([
            'message' => 'The activity has been saved.',
            'activity' => new ActivityResource($activity->refresh())
        ], 200);
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
