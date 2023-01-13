<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccountInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'role' => 'member',
            'account_id' => Account::factory(),
        ];
    }
}
