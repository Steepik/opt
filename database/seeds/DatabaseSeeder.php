<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($j = 1; $j <= 10; $j++) {
            \App\Brand::create([
                'name' => $faker->company,
                'image' => $faker->imageUrl('500', '500'),
            ]);
        }

        for($i = 0; $i <= 20; $i++) {
            \App\Tire::create([
                'brand_id' => $faker->numberBetween(1, 10),
                'name' => $faker->name,
                'image' => $faker->imageUrl('500', '500'),
                'code' => $faker->postcode,
                'twidth' => $faker->numberBetween(4, 10),
                'tprofile' => $faker->numberBetween(0, 24),
                'tdiameter' => $faker->numberBetween(0, 24),
                'load_index' => $faker->numberBetween(100, 300),
                'speed_index' => $faker->numberBetween(100, 300),
                'tseason' => 'winter',
                'model' => $faker->text(10),
                'model_class' => 'B+',
                'tcae' => $faker->swiftBicNumber(),
                'spike' => $faker->numberBetween(0, 1),
                'price_opt' => $faker->numberBetween(1000, 10000),
                'price_roz' => $faker->numberBetween(2000, 10000),
                'quantity' => $faker->numberBetween(0, 50)
            ]);
        }
    }
}
