<?php

namespace Tests\Feature\Api\v1\Accounts;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Setup\AccountFactory;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_get_its_projects_for_an_account()
    {
        [$account, $user] = AccountFactory::accountWithSanctumUser();
        ProjectFactory::projectsForAccountAndUser($account, $user, 3);

        $response = $this->getJson('/api/v1/accounts/' . $account->id . '/projects');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 3);
            });
    }

    /** @test */
    public function an_authenticated_user_cannot_get_projects_for_an_account_that_its_not_part_of()
    {
        AccountFactory::accountWithSanctumUser();
        $account2 = Account::factory()->create();

        $response = $this->getJson('/api/v1/accounts/' . $account2->id . '/projects');

        $response->assertUnauthorized();
    }
}
