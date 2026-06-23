<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-60 days', '+10 days');
        $deadline = fake()->dateTimeBetween('+10 days', '+90 days');

        return [
            'owner_id' => User::factory(),
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'start_date' => $startDate->format('Y-m-d'),
            'deadline' => $deadline->format('Y-m-d'),
        ];
    }
}
