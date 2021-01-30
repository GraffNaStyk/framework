<?php

use App\Facades\Http\Router;
use App\Facades\Http\Route;

Route::namespace('App\Controllers\Http', function () {
    Route::get('/', 'Index@index');
});

Router::run();
