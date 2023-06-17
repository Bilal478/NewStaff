<?php

use App\Http\Controllers\Accounts\ScreenshotController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Logout2Controller;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\download;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Subscriptions\SubscriptionController;
use App\Http\Controllers\Subscriptions\PaymentController;
use App\Http\Controllers\StripeController;
use App\Http\Livewire\Accounts\Billing\BillingInfo;
use App\Http\Livewire\Accounts\Account\AccountCreate;
use App\Http\Livewire\Accounts\Account\AccountEdit;
use App\Http\Livewire\Accounts\Activities\ActivitiesIndex;
use App\Http\Livewire\Accounts\Companies\CompaniesIndex;
use App\Http\Livewire\Accounts\Companies\CompaniesShow;
use App\Http\Livewire\Accounts\Welcome\Welcome;
use App\Http\Livewire\Accounts\Dashboard;
use App\Http\Livewire\Accounts\Departments\DepartmentsIndex;
use App\Http\Livewire\Accounts\Departments\DepartmentsShow;
use App\Http\Livewire\Accounts\Members\MembersIndex;
use App\Http\Livewire\Accounts\Members\MembersInvite;
use App\Http\Livewire\Accounts\Projects\ProjectsIndex;
use App\Http\Livewire\Accounts\Projects\ProjectsShow;
use App\Http\Livewire\Accounts\Reports\ReportsIndex;
use App\Http\Livewire\Accounts\Reports\DailyReportsIndex;
use App\Http\Livewire\Accounts\Tasks\TasksIndex;
use App\Http\Livewire\Accounts\Teams\TeamsIndex;
use App\Http\Livewire\Accounts\Teams\TeamsShow;
use App\Http\Livewire\Accounts\User\UserEdit;
use App\Http\Livewire\Auth\Invitation;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Home;
use App\Http\Livewire\Auth\Passwords\Confirm;
use App\Http\Livewire\Auth\Passwords\Email;
use App\Http\Livewire\Auth\Passwords\Reset;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Verify;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Mail\SubscriptionMail;
use Illuminate\Support\Facades\Mail;

use App\Http\Livewire\Accounts\PlansandPayment\PlansandPayment;

use App\Http\Livewire\SelectPlan;

//Route::view('/', 'welcome')->name('home');

//Route::get('/', WelcomeController::class)->name('home');

Route::view('/download', 'download')->name('download');

Route::get('/downloads/windows/', [download::class, 'index'])->name('download.index');
Route::get('/downloads/mac/', [download::class, 'macFile'])->name('download.macFile');

Route::get('/terms-and-conditions', [download::class, 'TermsAndConditions']);
Route::get('/hipaa', [download::class, 'hipaa']);

Route::middleware('guest:web')->group(function () {
    Route::get('login', Login::class)->name('login');

    Route::get('/', Home::class)->name('home');

	Route::get('register', Register::class)->name('register');
});

Route::group(['namespace' => 'Subscriptions'], function() {
    Route::get('plans', 'SubscriptionController@index')->name('plans');
});
	
    Route::get('plans2', [SubscriptionController::class, 'index']);
	Route::get('payments', [PaymentController::class, 'index']);
    Route::post('payments', [PaymentController::class, 'store']);
	
	Route::get('stripe', [StripeController::class, 'stripe']);
	Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');

Route::get('password/reset', Email::class)->name('password.request');
Route::get('password/reset/{token}', Reset::class)->name('password.reset');
Route::get('invitation/{accountInvitation}', Invitation::class)->name('invitation');

Route::middleware(['auth:web', 'account.verify'])->group(function () {
    Route::get('email/verify', Verify::class)->middleware('throttle:6,1')->name('verification.notice');
    Route::get('password/confirm', Confirm::class)->name('password.confirm');
    Route::post('logout', LogoutController::class)->name('logout');
    Route::post('logout2', Logout2Controller::class)->name('logout2');
	
	
	Route::get('/billing_information', [PlansandPayment::class, 'index'])->name('billing_information');
	Route::post('/billing_information', [PlansandPayment::class, 'payandcontinue']);
	Route::get('/thankyou', [Welcome::class, 'welcome'])->name('accounts.welcome');
	
	Route::get('/sendmail', [PlansandPayment::class, 'sendMail'])->name('sendMail');

    Route::get('dashboard', Dashboard::class)->name('accounts.dashboard');
    Route::get('projects', ProjectsIndex::class)->name('accounts.projects');
    Route::get('projects/{project}', ProjectsShow::class)->name('accounts.projects.show');
    Route::get('tasks', TasksIndex::class)->name('accounts.tasks');
    Route::get('activities', ActivitiesIndex::class)->name('accounts.activities');
    Route::get('screenshots/{user}/{filename}', ScreenshotController::class)->name('accounts.screenshots');
    Route::get('profile/edit', UserEdit::class)->name('profile.edit');
    Route::get('accounts/create', AccountCreate::class)->name('accounts.create');
    Route::get('reports', ReportsIndex::class)->name('accounts.reports');
    Route::get('dailyreports', DailyReportsIndex::class)->name('accounts.dailyreports');
	
    Route::get('departments/{department}', DepartmentsShow::class)->name('accounts.departments.show');
    Route::get('teams/{team}', TeamsShow::class)->name('accounts.teams.show');
    Route::get('companies/{company}', CompaniesShow::class)->name('accounts.companies.show');
    Route::get('cancelsubscription',[BillingInfo::class, 'cancel2'])->name('accounts.cancel2');
    Route::post('deleteseats',[BillingInfo::class, 'deleteseats'])->name('accounts.deleteseats');
    Route::post('/members', [MembersIndex::class, 'payandcontinue']);
	Route::post('/billing', [BillingInfo::class, 'payandcontinue']);

    Route::middleware('account.owner')->group(function () {
		
        //Route::view('report/preview', 'pdf.report', ['data' => App\Models\User::all()]);
        Route::get('teams', TeamsIndex::class)->name('accounts.teams');
        Route::get('departments', DepartmentsIndex::class)->name('accounts.departments');
        Route::get('companies', CompaniesIndex::class)->name('accounts.companies');
        Route::get('members', MembersIndex::class)->name('accounts.members');
        Route::get('settings', AccountEdit::class)->name('accounts.settings');
		Route::get('billing', BillingInfo::class)->name('accounts.billing');
    });
});

Route::view('/support', 'support')->name('support');


// Route::middleware('auth')->group(function () {
//     Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)->middleware('signed')->name('verification.verify');
//     Route::post('logout', LogoutController::class)->name('logout');
// });
Route::post('update/activity/{id}', function ($id, Request $request) {
// dd($request->all());
    $start = Carbon\Carbon::parse($request->date)->format('Y-m-d') . ' ' . $request->start_datetime;
    $end = Carbon\Carbon::parse($request->date)->format('Y-m-d') . ' ' . $request->end_datetime;
    // dd( $start);
    Activity::find($id)->update([
        'start_datetime' => $start,
        'end_datetime' => $end,
    ]);
    return back();
});

Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";

    $cleardebugbar = Artisan::call('storage:link');
    echo "storage linked<br>";
});