<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginUserLogsController extends Controller
{

    public function index($user_id=null){
        
       if($user_id){
        $logs = DB::table('login_user_logs')->where(['status'=>'true','userId'=>$user_id])->get();
       }
       else{
        $logs = DB::table('login_user_logs')->where('status','true')->get();
       }

        return api_response([
            $logs
        ], 200);
    }


    public function delete($id)
    {
        if(DB::table('login_user_logs')->where('id',$id)->delete()){
            return api_response([
                'success' => 'Record deleted'
            ], 200);
        }
        else{
            return api_response([
                'message' => 'Record not found'
            ], 200);
        }

        
    }
    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'userId' => ['required'],
            'deviceName' => ['required'],
            'status' => [Rule::in(['true', 'false'])]
        ]);

        if ($validator->fails()) {
            return api_response_errors($validator->errors(), 422);
        }

        if(!isset($request->status)){
            $request->status = false;
        }
        DB::table('login_user_logs')->insert(['token'=>$request->token,'userId'=>$request->userId,'deviceName'=>$request->deviceName,'status'=>$request->status]);

        return api_response([
            'success' => 'Record Added'
        ], 200);
    }
    

}
