<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Account;
use App\Models\Project;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $seconds = [];

        for ($i=0; $i <= 86400; $i+=600) {
            $seconds[] = $i;
        }

        return [
            'from' => $from = $this->faker->randomElement($seconds),
            'to' => $from + 600,
            'seconds' => $this->faker->numberBetween(0, 600),
            //'start_time' => null,
            //'end_time' => null,
            'date' => Carbon::now()
                ->subDays($this->faker->numberBetween(0, 2)),
            'keyboard_count' => $this->faker->numberBetween(10, 300),
            'mouse_count' => $this->faker->numberBetween(20, 150),
            'total_activity' => 0,
            'total_activity_percentage' => $this->faker->numberBetween(10, 99),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'account_id' => Account::factory(),
        ];
    }
}
