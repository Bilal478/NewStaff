<?php

namespace Tests\Feature\Api\v1\Accounts;

use App\Models\Account;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Setup\AccountFactory;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_get_all_its_task()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $project2 = ProjectFactory::projectsForAccountAndUser($account, $user);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'account_id' => $account->id])->each(function ($task) use ($user) {
            $task->assignUser($user);
        });
        Task::factory()->count(2)->create(['project_id' => $project2->id, 'account_id' => $account->id])->each(function ($task) use ($user) {
            $task->assignUser($user);
        });

        $response = $this->getJson("/api/v1/accounts/{$account->id}/tasks");

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 5);
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_get_all_tasks_for_an_account_that_its_not_part_of()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        ProjectFactory::projectsForAccountAndUser($account, $user);
        $account2 = Account::factory()->create();

        $response = $this->getJson("/api/v1/accounts/{$account2->id}/tasks");

        $response->assertUnauthorized();
    }

    /** @test */
    public function an_authenticated_user_can_complete_a_task_that_is_assigned()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task->id}/complete");

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function ($json) {
                    $json->where('message', 'The task has been marked as completed.')
                        ->has('task');
                });
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_complete_a_task_that_is_already_completed()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create([
            'completed' => true,
            'project_id' => $project->id,
            'user_id' => $user->id,
            'account_id' => $account->id,
        ]);

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task->id}/complete");

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function ($json) {
                    $json->where('message', 'The task has already been completed.')
                        ->has('task');
                });
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_complete_a_task_that_is_not_assigned()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => null,
            'account_id' => $account->id,
        ]);

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task->id}/complete");

        $response->assertUnauthorized();
    }
}
