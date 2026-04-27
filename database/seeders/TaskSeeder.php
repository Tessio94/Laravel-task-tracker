<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('categories')->get();

        foreach($users as $user) {
            $categories = $user->categories;

            Task::factory()
                ->count(rand(30, 50))
                ->for($user)
                ->for($categories->random())
                ->create();

            Task::factory()
                ->count(rand(5, 10))
                ->for($user)
                ->withoutCategory()
                ->create();

            Task::factory()
                ->count(rand(10, 20))
                ->for($user)
                ->for($categories->random())
                ->completed()
                ->create();

            Task::factory()
                ->count(rand(5, 10))
                ->for($user)
                ->for($categories->random())
                ->overdue()
                ->create();
        }
    }
}
