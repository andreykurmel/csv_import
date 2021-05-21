<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 10; $i++) {
            \App\DbProduct::create([
                'brand' => $faker->word(),
                'variant' => $faker->word(),
                'name' => $faker->name(),
                'price' => $faker->randomDigitNotZero(),
                'description' => $faker->paragraph(),
            ]);
        }
    }
}
