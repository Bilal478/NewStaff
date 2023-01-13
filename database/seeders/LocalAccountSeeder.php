<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Admin;
use App\Models\Account;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Screenshot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class LocalAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::factory()->create(['email' => 'admin@example.com']);

        $owner = User::factory()->create(['email' => 'owner@example.com']);

        $members = User::factory()->times(5)->create();

        $account = Account::factory()
            ->hasAttached($owner, ['role' => 'owner'])
            ->hasAttached($members, ['role' => 'member'])
            ->create();

        $project1 = Project::factory()
            ->hasAttached($members)
            ->create(['account_id' => $account->id]);

        $project2 = Project::factory()
            ->hasAttached($members)
            ->create(['account_id' => $account->id]);


        $members->each(function ($member) use ($account, $project1, $project2) {
            $tasks = Task::factory()
                ->count(3)
                ->state(new Sequence(
                    ['project_id' => $project1->id],
                    ['project_id' => $project2->id],
                ))
                ->create([
                    'account_id' => $account->id,
                    'user_id' => $member->id,
                ]);

            $tasks->each(function ($task) use ($member) {
                Activity::factory()
                    ->count(2)
                    ->create([
                        'task_id' => $task->id,
                        'user_id' => $member->id,
                        'project_id' => $task->project_id,
                        'account_id' => $task->account_id,
                    ])->each(function ($activity) use ($member) {
                        Screenshot::factory()->count(2)->withUserFolder($member)->create([
                            'activity_id' => $activity->id,
                            'account_id' => $activity->account_id
                        ]);
                    });
            });
        });


        // $owner2 = User::factory()->create(['email' => 'owner2@example.com']);

        // Account::factory()
        //     ->hasAttached($owner2, ['role' => 'owner'])
        //     ->hasAttached(User::factory()->times(4)->create(), ['role' => 'member'])
        //     ->create();
    }
}
