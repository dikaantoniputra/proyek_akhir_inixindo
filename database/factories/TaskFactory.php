<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['rendah', 'sedang', 'tinggi']),
            'status' => fake()->randomElement(['belum dimulai', 'dikerjakan', 'selesai']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}
