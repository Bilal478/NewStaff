<?php

namespace App\Http\Controllers\Api\v1\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    public function index(Account $account, Request $request)
    {
        $user = Auth::user();

        if ($user->isOwner()) {
            // Owner gets all departments under the account
            $departments = Department::where('account_id', $account->id)
                ->orderBy('title')
                ->get();
        } else {
            // Regular users get only their associated departments under the account
            $departments = $user->departments()
                ->where('account_id', $account->id)
                ->orderBy('title')
                ->get();
        }

        return api_response($departments, 200);
    }
}
