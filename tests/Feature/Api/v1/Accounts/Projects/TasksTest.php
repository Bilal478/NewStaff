<?php

namespace Tests\Feature\Api\v1\Accounts\Projects;

use App\Models\Account;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Setup\AccountFactory;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_get_its_task_for_a_projects()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        Task::factory()->count(3)->create(['project_id' => $project->id, 'account_id' => $account->id])->each(function ($task) use ($user) {
            $task->assignUser($user);
        });

        $response = $this->getJson("/api/v1/accounts/{$account->id}/projects/{$project->id}/tasks");

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 3);
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_get_tasks_for_an_account_that_its_not_part_of()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $account2 = Account::factory()->create();

        $response = $this->getJson("/api/v1/accounts/{$account2->id}/projects/{$project->id}/tasks");

        $response->assertUnauthorized();
    }
}
