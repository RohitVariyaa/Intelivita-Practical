<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)->create()->each(function ($user) {
            for ($i = 0; $i < rand(5, 20); $i++) {
                Activity::create([
                    'user_id' => $user->id,
                    'performed_at' => now()->subDays(rand(0, 30)),
                    'points' => 20
                ]);
            }
        });
    }
}
