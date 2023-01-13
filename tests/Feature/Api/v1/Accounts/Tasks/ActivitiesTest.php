<?php

namespace Tests\Feature\Api\v1\Accounts\Tasks;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Activity;
use App\Models\Screenshot;
use Tests\Setup\ImageFactory;
use Tests\Setup\AccountFactory;
use Tests\Setup\ProjectFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_add_activity_with_image_to_a_task_that_is_assigned()
    {
        $this->withoutExceptionHandling();

        Storage::fake('screenshots');
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'account_id' => $account->id]);

        $attributes = [
            'from' => 700,
            'to' => 1200,
            'seconds' => 500,
            'date' => Carbon::now()->format('m/d/Y'),
            'keyboard_count' => 100,
            'mouse_count' => 100,
            'total_activity' => 40,
            'total_activity_percentage' => 40,
            'screenshots' => [
                ImageFactory::BASE64,
                ImageFactory::BASE64,
            ],
        ];

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task->id}/activities", $attributes);

        Activity::first()->screenshots->each(function ($screenshot) {
            Storage::disk(Screenshot::STORAGE_DISK)->assertExists($screenshot->path);
        });

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function ($json) {
                    $json->where('message', 'The activity has been saved.')
                        ->has('activity', function ($json) {
                            $json->has('screenshots', 2)
                                ->etc();
                        });
                });
            });
    }

    /** @test */
    public function an_authenticated_user_can_add_activity_without_image_to_a_task_that_is_assigned()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'account_id' => $account->id]);

        $attributes = [
            'from' => 700,
            'to' => 1200,
            'seconds' => 500,
            'date' => Carbon::now()->format('m/d/Y'),
            'keyboard_count' => 100,
            'mouse_count' => 100,
            'total_activity' => 40,
            'total_activity_percentage' => 40,
            'screenshots' => null,
        ];

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task->id}/activities", $attributes);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function ($json) {
                    $json->where('message', 'The activity has been saved.')
                        ->has('activity');
                });
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_add_activity_to_a_task_that_is_not_assigned()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        $project = ProjectFactory::projectsForAccountAndUser($account, $user);
        $task = Task::factory()->create(['project_id' => $project->id, 'user_id' => $user->id, 'account_id' => $account->id]);
        $user2 = User::factory()->create();
        $account->addMember($user2);
        $task2 = Task::factory()->create(['project_id' => $project->id, 'user_id' => $user2->id, 'account_id' => $account->id]);

        $attributes = [
            'from' => 700,
            'to' => 1200,
            'seconds' => 500,
            'date' => Carbon::now()->format('m/d/Y'),
            'image' => null,
            'keyboard_count' => 100,
            'mouse_count' => 100,
            'total_activity' => 40,
            'total_activity_percentage' => 40,
        ];

        $response = $this->postJson("/api/v1/accounts/{$account->id}/tasks/{$task2->id}/activities", $attributes);

        $response->assertUnauthorized();
    }
}
