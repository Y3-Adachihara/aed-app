<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Aed;

class AedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 1; $i <= 10; $i ++) {

            // -0.01 ~ 0.01までの乱数を生成。
            $latitude_randNum = 34.837 + rand(-100, 100) / 10000;
            $longitude_randNum = 138.179 + rand(-100, 100) / 10000;
            
            Aed::create([
                'name' => fake()->secondaryAddress(),
                'postcode' => fake()->postcode(),
                'prefecture' => fake()->prefecture(),
                'municipality' => fake()->city(),
                'address' => fake()->streetAddress(),
                'description' => fake()->sentence(),
                'latitude' => $latitude_randNum,
                'longitude' => $longitude_randNum,
            ]);
        }
        
    }
}
