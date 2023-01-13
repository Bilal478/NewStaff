<?php

namespace Tests\Feature\Api\v1;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_get_its_data()
    {
        $user = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@example.com',
        ]);
        Account::factory()->hasAttached($user, ['role' => 'member'])->create(['name' => 'Acme']);
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v1/user');

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function($json) {
                    $json->has('user', function($json) {
                        $json->where('firstname', 'John')
                            ->where('lastname', 'Doe')
                            ->where('email', 'johndoe@example.com');
                    })->has('accounts.0', function($json) {
                        $json->where('id', 1)
                            ->where('name', 'Acme');
                    });
                });
            });
    }

    /** @test */
    public function an_unauthenticated_user_cannot_get_its_data()
    {
        $user = User::factory()->create();
        $response = $this->getJson('/api/v1/user');

        $response->assertUnauthorized();
    }
}
