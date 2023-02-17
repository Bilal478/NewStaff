<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\Dashboard;
use App\Http\Livewire\Auth\Admin\Login;
use App\Http\Livewire\Admin\User\UserEdit;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Admin\Invitation;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Livewire\Admin\Members\MembersIndex;
use App\Http\Livewire\Admin\Settings\SettingsIndex;
Route::middleware('guest:admin')->group(function () {
    Route::get('login', Login::class)->name('login');
});

Route::get('password/reset', Email::class)->name('password.request');
Route::get('password/reset/{token}', Reset::class)->name('password.reset');
Route::get('invitation/{adminInvitation}', Invitation::class)->name('invitation');

Route::middleware('auth:admin')->group(function () {
    //Route::get('email/verify', Verify::class)->middleware('throttle:6,1')->name('verification.notice');
    Route::get('password/confirm', Confirm::class)->name('password.confirm');
    Route::post('logout', LogoutController::class)->name('logout');

    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('members', MembersIndex::class)->name('members');
    Route::get('settings', SettingsIndex::class)->name('settings');
    Route::get('profile/edit', UserEdit::class)->name('profile.edit');
});
