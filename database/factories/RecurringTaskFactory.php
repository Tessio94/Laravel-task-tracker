<?php

namespace Database\Factories;

use App\Enums\TaskFrequency;
use App\Models\Category;
use App\Models\RecurringTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecurringTask>
 */
class RecurringTaskFactory extends Factory
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
            'title' => fake()->sentence(rand(3, 8)),
            'description' => fake()->optional(0.7)->paragraph(),
            'frequency' => TaskFrequency::Daily->value,
            'frequency_config' => null,
            'start_date' => now()->startOfDay(),
            'end_date' => null,
        ];
    }

    public function daily(): static
    {
        return $this->state(fn(array $attributes) => [
            'frequency' => TaskFrequency::Daily->value,
            'frequency_config' => null,
        ]);
    }

    public function weekdays(): static
    {
        return $this->state(fn(array $attributes) => [
            'frequency' => TaskFrequency::Weekdays->value,
            'frequency_config' => null,
        ]);
    }

    public function weekly(array $days = ['monday']): static
    {
        return $this->state(fn(array $attributes) => [
            'frequency' => TaskFrequency::Weekly->value,
            'frequency_config' => ['days' => $days],
        ]);
    }

    public function monthly(int $day = 1): static
    {
        return $this->state(fn(array $attributes) => [
            'frequency' => TaskFrequency::Monthly->value,
            'frequency_config' => ['day' => $day],
        ]);
    }

    public function withEndDate(?string $endDate = '+1 year'): static
    {
        return $this->state(fn(array $attributes) => [
            'end_date' => $endDate ? now()->modify($endDate) : null,
        ]);
    }
}
