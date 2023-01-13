<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_logout()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $response = $this->postJson('/api/v1/logout');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where('message', 'Session closed.');
            });
    }

    /** @test */
    public function an_unauthenticated_user_cannot_logout()
    {
        $response = $this->postJson('/api/v1/logout');

        $response->assertUnauthorized();
    }
}
