<?php

use Kielabokkie\Apise\Models\ApiLog;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ApiLog::class, function (Faker\Generator $faker) {
    return [
        'correlation_id' => $faker->uuid,
        'method' => $faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
        'protocol_version' => '1.1',
        'host' => $faker->url,
        'uri' => '/',
        'status_code' => 200,
        'reason_phrase' => 'OK',
    ];
});
