<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->is('admin/*')) {
            Session::forget('user_id');
            Auth::guard('admin')->logout();
        } else {
            cookie()->queue(cookie()->forget('auth_token'));
            Session::forget('user_id');
            Auth::guard('web')->logout();
        }

        return redirect(route('home'));
    }
}
