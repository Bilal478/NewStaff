<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Account;
use App\Models\Activity;
use App\Models\Screenshot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScreenshotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Screenshot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => null,
            'activity_id' => Activity::factory(),
            'account_id' => Account::factory(),
        ];
    }

    public function withUserFolder(User $user)
    {
        if (! Storage::disk('screenshots')->exists($user->id)) {
            Storage::disk('screenshots')->makeDirectory($user->id);
        }


        return $this->state(function (array $attributes) use ($user) {
            return [
                'path' => $user->id . '/' . $this->faker->image('storage/app/screenshots/' . $user->id, 1280, 800, null, false),
            ];
        });
    }
}
