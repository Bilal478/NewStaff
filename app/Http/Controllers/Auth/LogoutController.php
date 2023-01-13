<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->is('admin/*')) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        return redirect(route('home'));
    }
}
