<?php

namespace App\Http\Controllers\Api\v1\Accounts\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Task;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function store(Request $request, $account, $project)
{
    // ✅ Merge route parameters into the request
    $request->merge([
        'project_id' => $project,
        'account_id' => $account,
    ]);

    // ✅ Validate required fields
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'project_id' => 'required|exists:projects,id',   // comes from URL
        'account_id' => 'required|exists:accounts,id',   // comes from URL
        'completed' => 'sometimes|boolean',              // optional, defaulted below
    ]);

    // ✅ Default `completed` to false if not provided
    $validated['completed'] = $validated['completed'] ?? false;

    // ✅ Set department_id from project
    $validated['department_id'] = \App\Models\Project::where('id', $validated['project_id'])->pluck('department_id')->first();

    // ✅ Create task
    $task = \App\Models\Task::create($validated);

    return api_response(['task' => new \App\Http\Resources\TaskResource($task)], 201, 'Task Created');
}



public function destroy($accountId, $projectId, $taskId)
{
    $task = Task::findOrFail($taskId);

    $task->delete();

    return response()->json(['message' => 'Task deleted successfully.'], 200);
}
public function update(Request $request, $account, $project, $taskId)
{
    $task = \App\Models\Task::findOrFail($taskId);

    // Merge URL values into request for validation
    $request->merge([
        'project_id' => $project,
        'account_id' => $account,
    ]);

    // ✅ Match validation with `store()` method
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'project_id' => 'required|exists:projects,id',
        'account_id' => 'required|exists:accounts,id',
        'completed' => 'sometimes|boolean',
        'activity_id' => 'nullable|integer|exists:activities,id',
        'tracking_time' => 'nullable|string', // HH:MM:SS
    ]);

    // ✅ Set default if `completed` is not sent
    $validated['completed'] = $validated['completed'] ?? false;

    // ✅ Optional: Update activity time
    if (!empty($validated['activity_id']) && !empty($validated['tracking_time'])) {
        sscanf($validated['tracking_time'], "%d:%d:%d", $hours, $minutes, $seconds);
        $time = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

      $activity = \App\Models\Activity::find($validated['activity_id']);
            if ($activity) {
                $activity->update(['seconds' => $time]);
            }

    }

    // ✅ Set department_id from project
    $validated['department_id'] = \App\Models\Project::where('id', $validated['project_id'])->value('department_id');

    // ✅ Update the task
    $task->update($validated);

    return response()->json(['message' => 'Task updated successfully.'], 200);
}


}
