<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Setting;
use App\Batch;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'user_type' => '4',
        'school_name' => 'Head Quarter '.mt_rand(100, 999),
        'slug' => 'header-quarter-'.mt_rand(100, 999),
        'address' => 'Nepal',
        'phone_no' => '9852012345',
        'email' => 'admin@gmail.com',
        'principal_name' => $faker->name,
        'url' => 'http://maindoamin.com.np',
        'image' => 'image.jpg',
        'is_active' => '1',
        'created_by' => '1',
        'created_at_np' => date('Y-m-d H:i:s'),
    ];
});

$factory->define(Batch::class, function (Faker $faker) {
    return [
        'name' => '2077',
        'slug' => '2077',
        'sort_id' => '1',
        'created_by' => '1',
        'is_active' => '1',
        'school_id' => '1',
        'created_at_np' => date('Y-m-d H:i:s'),
    ];
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'middle_name' => 'Prasad',
        'last_name' => 'Dahal',
        'email' => 'admin@gmail.com',
        // 'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$eAEF24Uto64jknFqlzRgXOZA7tWIiWNo3NB3dSpgkzQseTHOL7aIK', // admin123
        // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'phone_no' => '9852012345',
        'user_type' => '4',
        'school_id' => '1',
        'batch_id' => '1',
        'is_active' => '1',
        'created_at_np' => date('Y-m-d H:i:s'),
    ];
});
