<?php

namespace Database\Seeders;

use App\Enums\TaskFrequency;
use App\Models\RecurringTask;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecurringTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('categories')->get();
        $frequencyies = TaskFrequency::cases();

        foreach($users as $user) {
            $categories = $user->categories;

            for($i = rand(1, 15); $i <= 20; $i++) {
                $frequency = fake()->randomElement($frequencyies);
                $recurringTask = RecurringTask::factory()->for($categories->random());

                match($frequency) {
                    TaskFrequency::Daily => $recurringTask->daily(),
                    TaskFrequency::Weekdays => $recurringTask->weekdays(),
                    TaskFrequency::Weekly => $recurringTask->weekly(),
                    TaskFrequency::Monthly => $recurringTask->monthly(),
                };

                $recurringTask->create();
            }
        }
    }
}
