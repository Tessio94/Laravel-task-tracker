<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\RecurringTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'recurring_task_id' => null,
            'title' => fake()->sentence(rand(3, 8)),
            'description' => fake()->optional(0.7)->paragraph(),
            'task_date' => fake()->dateTimeBetween('-30 days', '+30 days'),
            'completed_at' => null
        ];
    }

    public function forRecurringTask(RecurringTask $recurringTask): static
    {
        return $this->state(fn (array $attributes) => [
            'recurring_task_id' => $recurringTask->id,
            'user_id' => $recurringTask->user_id,
            'category_id' => $recurringTask->category_id,
            'title' => $recurringTask->title,
            'description' => $recurringTask->description,
        ]);
    }

    public function completed(): static
    {
        return $this->state(
            function() {
                $taskDate = fake()->dateTimeBetween('-30 days', 'now')->setTime(0, 0);

                return [
                    'task_date'    => $taskDate,
                    'completed_at' => fake()->dateTimeBetween($taskDate, 'now'),
                ];
            }
        );
    }

    public function withoutCategory(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'category_id' => null,
            ]
        );
    }

    public function today(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'task_date' => now()->startOfDay(),
            ]
        );
    }

    public function overdue(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'task_date'    => fake()->dateTimeBetween('-60 days', '-1 day')->setTime(0, 0),
                'completed_at' => null,
            ]
        );
    }
}
