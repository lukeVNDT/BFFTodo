<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\TodayTsk;

class TodaytskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 5; $i++) { 
            TodayTsk::create([
                'completed' => false,
                'title' => $faker->sentence($nbWords = 4, $varialbeWords = false),
                'approved' => false,
                'taskId' => Str::random(10)
            ]);
        }
    }
}
