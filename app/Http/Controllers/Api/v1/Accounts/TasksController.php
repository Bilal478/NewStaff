<?php

namespace App\Http\Controllers\Api\v1\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Account;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TasksController extends Controller
{
    public function index(Account $account, Request $request)
    {
        return api_response(TaskResource::collection($request->user()->tasks), 200);
    }

    public function complete(Account $account, Task $task)
    {
        if (Gate::denies('complete-task', $task)) {
            return api_response_unauthorized();
        }

        if ($task->isCompleted()) {
            return api_response([
                'message' => 'The task has already been completed.',
                'task' => new TaskResource($task),
            ], 200);
        }

        $task->complete();

        return api_response([
            'message' => 'The task has been marked as completed.',
            'task' => new TaskResource($task),
        ], 200);
    }
}
