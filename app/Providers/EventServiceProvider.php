<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Project;
use App\Observers\TaskObserver;
use Illuminate\Auth\Events\Login;
use App\Observers\ProjectObserver;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use App\Listeners\SetAccountInSession;
use Illuminate\Auth\Events\Registered;
use App\Listeners\ForgetAccountInSession;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            SetAccountInSession::class,
        ],
        Logout::class => [
            ForgetAccountInSession::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Task::observe(TaskObserver::class);
        Project::observe(ProjectObserver::class);
    }
}
