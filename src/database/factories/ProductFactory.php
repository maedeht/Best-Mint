<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Product::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'price' => $faker->numberBetween(10,1000),
        'description' => $faker->paragraph,
        'qty' => $faker->numberBetween(10,100),
        'category' => $faker->randomElement(['shoes', 'shirts', 'bags', 'trousers', 'glasses']),
    ];
});
