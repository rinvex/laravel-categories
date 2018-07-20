<?php

declare(strict_types=1);

use Faker\Generator as Faker;

$factory->define(Rinvex\Categories\Models\Category::class, function (Faker $faker) {
    return ['name' => [$faker->languageCode => $faker->title], 'slug' => $faker->slug];
});
