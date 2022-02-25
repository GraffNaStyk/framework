<?php

use App\Facades\Http\Router\Route;

Route::alias('/api', function () {
    Route::namespace('App\Controllers\Api', function () {
        Route::post('/', 'Example@index');
    });
});
