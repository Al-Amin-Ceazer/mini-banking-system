<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;
use Faker\Provider\Address;

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

$factory->define(Customer::class, function (Faker $faker) {

    $gender = $faker->randomElement(['male', 'female']);

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'dob' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'sex' => $gender,
        'personal_code' => $faker->ssn,
        'email' => $faker->unique()->safeEmail,
        'street_address' => $faker->streetAddress,
        'city' => $faker->city,
        'zipcode' => Address::postcode(),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});
