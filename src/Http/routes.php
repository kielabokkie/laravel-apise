<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ApiseController@index');


Route::prefix('api')->group(function () {
    Route::get('/logs', 'ApiseController@logs');
    Route::get('/logs/{id}', 'ApiseController@logs');
});
