<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes v1 (Registered routes with sanctum authentication.)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('login', 'v1\Auth\LoginController');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'v1\Auth\LogoutController');
        Route::get('user', 'v1\UserController');
        Route::get('user/validate_user', 'v1\UserController@validate_user');
        Route::get('user/punch_code_verification', 'v1\UserController@punch_code_verification');
        Route::get('user/change_state_punch_code', 'v1\UserController@change_state_punch_code');
        Route::get('company/members', 'v1\CompanyController@members');

        Route::get('user/logs/{user_id?}', 'v1\Auth\LoginUserLogsController@index');
        Route::post('user/logs', 'v1\Auth\LoginUserLogsController@store');
        Route::post('user/logs/delete/{id}', 'v1\Auth\LoginUserLogsController@delete');

        Route::get('accounts/{account}/projects/getbypro', 'v1\Accounts\ProjectsController@getbypro');
      
        Route::middleware('api.account')->group(function () {
           
            Route::get('accounts/{account}/projects', 'v1\Accounts\ProjectsController@index');
           
            Route::get('accounts/{account}/projects/{project}/tasks', 'v1\Accounts\Projects\TasksController@index');
            Route::get('accounts/{account}/tasks', 'v1\Accounts\TasksController@index');
            Route::post('accounts/{account}/tasks/{task}/complete', 'v1\Accounts\TasksController@complete');
            
			Route::post('accounts/{account}/tasks/{task}/activities', 'v1\Accounts\Tasks\ActivitiesController@store');
           
			Route::post('accounts/{account}/projects/{project}/activities', 'v1\Accounts\Projects\ActivitiesController@store');
        });
    });
	
	Route::get('neo_settings','v1\NeoSettings\APINeoSettings@index');
	Route::post('report_an_error','v1\Report_error\ReportAnError@index');
	
});

