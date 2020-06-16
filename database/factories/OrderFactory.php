<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'account_id'=> $faker->account_id,
        'cart'=> $faker->cart,
        'address'=>$faker->address,
        'name'=>$faker->name,
        'paymentId'=>$faker->paymentId,
        'completed'=>$faker->completed
    ];
});
