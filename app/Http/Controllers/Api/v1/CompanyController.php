<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Account;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function members(Request $request)
    {
        $res = array();
        $validator = Validator::make($request->all(), [
            'company_id' => ['required'],
            'date' => ['required'],
        ]);

        $account = Account::where('id',$request->company_id)->first();
      
        if($account)
        {
            $users=$account->users()->get();
            return api_response(MemberResource::collection($users),200);
        }else{
            return api_response(array(),200);
        }
      
    }
}
