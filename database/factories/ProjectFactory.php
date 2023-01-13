<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'label' => $this->faker->word,
            'category' => $this->faker->word,
            'time_expense_tracking' => false,
            'account_id' => Account::factory(),
            'user_id' => null,
        ];
    }
}
