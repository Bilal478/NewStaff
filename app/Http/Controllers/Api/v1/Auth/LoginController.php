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
use Intervention\Image\Facades\Image;
use App\Jobs\ConvertImagesJob;
use App\Models\Screenshot;

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
        $user_subscriptions = $user->subscriptions()->active()->get();
        // dd( $user_subscriptions);
        if ($user_subscriptions->isEmpty()) {
            return api_response_errors([
                'subscription' => ['User has no active subscriptions.'],
            ], 401);
        }

        $account=$user->accounts();
        $account_id=null;

        if($account)
        {
            $account_id=$account->first()->id;
        }
        $owner_id_query = DB::table('account_user')
        ->where('account_id', $account_id)
        ->first();	
        $count_subs = DB::table('subscriptions')
        ->where('user_id', $owner_id_query->user_id)
        ->where('stripe_status', '!=', 'canceled')
        ->get();

        if(count($count_subs) == '0'){
            return api_response_errors([
            'subscription' => ['User has no active subscriptions.'],
            ], 401);
        }
        $user_role = DB::table('account_user')
        ->where('user_id', '=', $user->id)->pluck('role')->first();

        $companies = $user->accounts()->get(['accounts.id', 'accounts.name']);
        $companies_list = $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'company_name' => $company->name,
            ];
        });

        return api_response([
            'token' => $user->createToken($request->device_name)->plainTextToken, 'UserID' => $user['id'],
            'role' => $user_role,
            'companies_list' => $companies_list,
        ], 200);
    }

    public function PngToWebp(){
        ConvertImagesJob::dispatch();
	}
    public function screenShotRename(){
        ConvertImagesJob::dispatch();
	}
}
