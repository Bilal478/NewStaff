<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

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
            'due_date' => null,
            'label' => $this->faker->word,
            'time_estimation' => 0,
            'hidden_clients' => false,
            'high_priority' => false,
            'default' => false,
            'completed' => false,
            'user_id' => null,
            'creator_id' => null,
            'updater_id' => null,
            'project_id' => Project::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
