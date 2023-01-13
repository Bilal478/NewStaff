<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __invoke(Request $request)
    {
        return api_response([
            'user' => $request->user()->only(['firstname', 'lastname', 'email']),
            'accounts' => AccountResource::collection($request->user()->accounts),
        ], 200);
    }

    public function validate_user(Request $request)
    {
        $result = "The provided credentials are correct.";
        $rol = "";

        $validator = Validator::make($request->all(), [
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return api_response_errors($validator->errors(), 422);
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $result = 'The provided credentials are incorrect.';
        } else {
            $rol = $user->getRole();
        }

        return api_response([
            'result' => $result,
            'role' => $rol,
        ], 200);
    }


    public function punch_code_verification(Request $request)
    {
        $member_account_id=-1;
        $supervisor_account_id=0;
        $result = "";
        $member_id = "";
        $member_firstname = "";
        $member_lastname = "";

        //get member
        $member = User::where('punchin_pin_code', $request->punching_pin_code)->get()->first();

        //get supervisor
        $supervisor = User::where('id', $request->supervisor_id)->get()->first();
                
        //get member account
        if($member){
            $member_account = DB::table('account_user')
            ->where('user_id', '=', $member->id)
            ->get()->first();
            $member_account_id=$member_account->account_id;
        }

        //get supervisor account
        if($supervisor)
        {
            $supervisor_account = DB::table('account_user')
            ->where('user_id', '=', $supervisor->id)
            ->get()->first();
            $supervisor_account_id=$supervisor_account->account_id;
        }

        //result api
        if ($member_account_id <> $supervisor_account_id) {
            $result = "Supervisor does not belogs to the user company";
        } else {
            $member_id = $member->id;
            $member_firstname = $member->firstname;
            $member_lastname = $member->lastname;
            $result = "Supervisor belogs to the user company";
        }

        return api_response([
            'member_id' => $member_id,
            'member_firstname' => $member_firstname,
            'member_lastname' => $member_lastname,
            'result' => $result,
        ], 200);
    }

    public function change_state_punch_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
            'state' => ['required'],
        ]);

        if ($validator->fails()) {
            return api_response_errors($validator->errors(), 422);
        }

        $user = User::where('id',$request->user_id)->first();
        $user->punchin_pin_code_active=$request->state;
        $user->save();

        return api_response("ok", 200);
    }

}
