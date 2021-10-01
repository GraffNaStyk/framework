<?php

use App\Facades\Http\Router\Route;

Route::alias('/api/v1', function () {
    Route::namespace('App\Controllers\Api', function () {
        Route::get('/', 'Example@index');
    });
});
