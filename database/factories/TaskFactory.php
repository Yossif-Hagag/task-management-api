<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $assignee = User::where('role', RoleEnum::USER)->inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'assignee_id' => $assignee?->id ?? User::factory(),
            'status' => $this->faker->randomElement(TaskStatus::values()),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 weeks'),
        ];
    }
}
