<?php

namespace App\Http\Controllers\Api\v1\Accounts\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TasksController extends Controller
{
    public function index(Account $account, Project $project, Request $request)
    {
        if (Gate::denies('view-project-tasks', $project)) {
            return api_response_unauthorized();
        }

        $tasks = $project->tasks()->where('user_id', $request->user()->id)->get();

        return api_response(TaskResource::collection($tasks), 200);
    }
}
