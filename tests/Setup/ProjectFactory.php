<?php

namespace Tests\Setup;

use App\Models\Account;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProjectFactory
{
    /**
     * Create a project for an account and assigned a user to the project.
     *
     * @param  Account $account
     * @param  User $user
     * @param  int $quantity
     * @return Project $project
     */
    public static function projectsForAccountAndUser(Account $account, User $user, $quantity = 1)
    {
        if ($quantity === 1) {
            $project = Project::factory()->create(['account_id' => $account->id]);
            $project->addMemeber($user->id);

            return $project;
        }

        return Project::factory()->count($quantity)->create(['account_id' => $account->id])->each(function($project) use ($user) {
            $project->addMemeber($user->id);
        });
    }
}
