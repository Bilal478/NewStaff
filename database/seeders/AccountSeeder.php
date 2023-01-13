<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Admin;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin::factory()->create(['email' => 'admin@example.com']);

        $this->accountWithProjetsAndTasks('owner@example.com');
    }

    public function accountWithProjetsAndTasks($ownerEmail): void
    {
        $owner = User::factory()->create(['email' => $ownerEmail]);
        $member1 = User::factory()->create(['firstname' => 'jose', 'lastname' => 'g', 'email' => 'jose@example.com',]);
        $member2 = User::factory()->create(['firstname' => 'manuel', 'lastname' => 'o', 'email' => 'manuel@example.com',]);
        $member3 = User::factory()->create(['firstname' => 'jerry', 'lastname' => 'o', 'email' => 'jerry@example.com',]);
        // Create account
        $account = Account::factory()
            ->hasAttached($owner, ['role' => 'owner'])
            ->hasAttached($member1, ['role' => 'member'])
            ->hasAttached($member2, ['role' => 'member'])
            ->hasAttached($member3, ['role' => 'member'])
            ->create();

        $this->projectAndTasks($account, $owner);
        $this->projectAndTasks($account, $member1);
        $this->projectAndTasks($account, $member2);
        $this->projectAndTasks($account, $member3);
    }

    public function projectAndTasks($account, $user)
    {
        // Create projects for user
        Project::factory()->count(5)->create(['account_id' => $account->id])->each(function ($project) use ($user, $account) {
            $project->addMemeber($user->id);

            // Create task for project and user
            Task::factory()->count(5)->create(['project_id' => $project->id, 'account_id' => $account->id])->each(function ($task) use ($user) {
                $task->assignUser($user);
            });
        });
    }
}
