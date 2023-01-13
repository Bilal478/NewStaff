<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use App\Models\Account;
use App\Models\Project;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-project-tasks', function (User $user, Project $project) {
            return $project->hasUser($user);
        });

        Gate::define('complete-task', function (User $user, Task $task) {
            return $task->isAssignedToUser($user);
        });

        Gate::define('store-task-activity', function (User $user, Task $task) {
            return $task->isAssignedToUser($user);
        });

        Gate::define('store-project-activity', function (User $user, Project $project) {
            return $project->hasUser($user);
        });

        Gate::define('delete-account-member', function (User $user, Account $account) {
            if ($account->users()->count() === 1) {
                return false;
            }

            if ($account->usersWithRole()->where('role', 'owner')->count() === 1) {
                return true;
            }

            return true;
        });
    }
}
