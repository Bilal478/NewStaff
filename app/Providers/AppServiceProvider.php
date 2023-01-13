<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

	// $this->app->bind('path.public',function(){
	//   return'/home/medianeo/public_html';
	//   });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	Schema::defaultStringLength(191);
        Blade::component('layouts.app', 'layout-app');
        Blade::component('layouts.landing-page', 'layout-landing-page');

        Blade::if('role', function ($roles, $resourceId = null) {
            if (is_string($roles)) {
                return session()->get('account_role') == $roles
                    || $resourceId == Auth::guard('web')->user()->id;
            }

            if (is_array($roles)) {
                return in_array(session()->get('account_role'), $roles)
                    || $resourceId == Auth::guard('web')->user()->id;
            }
        });
    }
}
