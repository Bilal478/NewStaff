<?php

namespace App\Http\Controllers\Accounts;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScreenshotController extends Controller
{
    public function __invoke(User $user, string $filename)
    {
        if ($user->isOwner() || $user->isManager() || $user->id == Auth::user()->id) {

            $path = config('filesystems.disks.screenshots.root') . '/' . $user->id . '/' . $filename;

            return response()->file($path);
        }

        return abort(404);
    }
}
