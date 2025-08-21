<?php

namespace App\Http\Controllers\Api\v1\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    public function index(Account $account, Request $request)
    {		
	
       //$projects = $request->user()->projects()->where('account_id', $account->id)->get();
		
       // return api_response(ProjectResource::collection($projects), 200);	
        
       $projects = DB::table('projects')
       ->where('projects.account_id', '=', $account->id)
       ->where('projects.deleted_at', '=', NULL)
       ->join('project_user', 'projects.id', '=', 'project_user.project_id')
       ->where('project_user.user_id', '=', $request->user()->id)
       ->select('projects.*')
       ->get();
       
			
		return api_response(ProjectResource::collection($projects),200);
    }

	public function getbypro(Request $request)
    {					
	//	dd($request->account->hasUser($request->user()));
		return api_response($request->user(),200);
    }
    public function store(Request $request, Account $account)
    {
    $request->merge(['account_id' => $account->id]);
    $rules = [
        'project_number' => 'nullable|numeric|max:250',
        'client_name' => 'nullable|string|max:250',
        'title' => 'required|string|max:250',
        'description' => 'required|string|max:500',
        'department_id' => 'required|string|max:30',
        'account_id' => 'required|exists:accounts,id',
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized – user not authenticated',
        ], 401);
    }

    $project = new Project($request->only([
        'project_number',
        'client_name',
        'title',
        'description',
        'department_id',
        'account_id',
    ]));
    $project->save();

    DB::table('project_user')->insert([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Project created successfully',
        'data' => $project,
    ], 201);
    }
    public function destroy(Request $request, Account $account, $projectId)
    {
    $project = Project::where('id', $projectId)
                      ->where('account_id', $account->id)
                      ->first();

    if (!$project) {
        return response()->json([
            'status' => false,
            'message' => 'Project not found or does not belong to the account.',
        ], 404);
    }

    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized – user not authenticated',
        ], 401);
    }

    if (!$user->accounts->contains('id', $project->account_id)) {
    return response()->json([
        'status' => false,
        'message' => 'Unauthorized – You do not belong to this project’s account.',
    ], 403);
    }
    $role = $user->getAccountRole($project->account_id);
    if (!in_array($role, ['owner', 'manager'])) {
    return response()->json([
        'status' => false,
        'message' => 'Unauthorized – Only owners or managers can delete this project.',
    ], 403);
    }

    $project->delete();

    return response()->json([
        'status' => true,
        'message' => 'Project deleted successfully.',
    ], 200);
    }

    public function update(Request $request, Account $account, $projectId)
    {
    $project = Project::where('id', $projectId)
                  ->where('account_id', $account->id)
                  ->first();

    if (!$project) {
        return response()->json([
            'status' => false,
            'message' => 'Project not found',
        ], 404);
    }

    $rules = [
        'project_number' => 'nullable|numeric|max:250',
        'client_name' => 'nullable|string|max:250',
        'title' => 'required|string|max:250',
        'description' => 'required|string|max:500',
        'department_id' => 'required|string|max:30',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = Auth::user();
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized – user not authenticated',
        ], 401);
    }

    $project->update($request->only([
        'project_number',
        'client_name',
        'title',
        'description',
        'department_id',
    ]));

    DB::table('project_user')->updateOrInsert([
        'project_id' => $project->id,
        'user_id' => $user->id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Project updated successfully',
        'data' => $project,
    ], 200);
    }
}
