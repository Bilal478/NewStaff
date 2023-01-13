<?php

namespace Tests\Feature\Api\v1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_login()
    {
        User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'device_name' => 'desktop',
        ]);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function ($json) {
                    $json->has('token');
                });
            });
    }

    /** @test */
    public function a_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create(['email' => 'johndoe@example.com']);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'wrong@email.com',
            'password' => 'password',
            'device_name' => 'desktop',
        ]);

        $response->assertUnauthorized()
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors', function ($json) {
                    $json->where('email', ['The provided credentials are incorrect.']);
                });
            });
    }

    /** @test */
    public function a_user_cannot_login_without_email_password_and_device_name()
    {
        $response = $this->postJson('/api/v1/login');

        $response->assertStatus(422)
            ->assertJson(function (AssertableJson $json) {
                $json->has('errors', function ($json) {
                    $json->where('email', ['The email field is required.'])
                        ->where('password', ['The password field is required.'])
                        ->where('device_name', ['The device name field is required.']);
                });
            });
    }
}
