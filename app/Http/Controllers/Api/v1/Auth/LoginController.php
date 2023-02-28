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

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            // 'device_name' => ['required', Rule::in(['desktop'])],
        ]);

        if ($validator->fails()) {
            return api_response_errors($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return api_response_errors([
                'email' => ['The provided credentials are incorrect.'],
            ], 401);
        }

        $account=$user->accounts();
        $account_id=null;

        if($account)
        {
            $account_id=$account->first()->id;
        }

        $user_role = DB::table('account_user')
        ->where('user_id', '=', $user->id)->pluck('role')->first();
        return api_response([
            'token' => $user->createToken($request->device_name)->plainTextToken, 'UserID' => $user['id'],
            'role' => $user_role,
            'company_id' => $account_id,
        ], 200);
    }
}
