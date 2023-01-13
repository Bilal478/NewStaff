<?php

namespace App\Http\Controllers\Api\v1\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    public function index(Account $account, Request $request)
    {		
	
       //$projects = $request->user()->projects()->where('account_id', $account->id)->get();
		
       // return api_response(ProjectResource::collection($projects), 200);	
	
			$projects = DB::table('projects')
				->where('projects.account_id', '=', $account->id)
				->join('tasks', 'projects.id', '=', 'tasks.project_id')
				->where('tasks.user_id', '=', $request->user()->id)
				->select('projects.*')
			->get();
			
			
		return api_response(ProjectResource::collection($projects),200);
    }

	public function getbypro(Request $request)
    {					
	//	dd($request->account->hasUser($request->user()));
		return api_response($request->user(),200);
    }
}
