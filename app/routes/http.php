<?php

use App\Facades\Http\Router\Route;

Route::when('/admin', '/dash');

Route::namespace('App\Controllers\Http', function () {
    Route::get('/', 'Index@index');
});

Route::alias('/admin', function () {
    Route::namespace('App\Controllers\Admin', function () {
        //@Route login
        Route::get('/login', 'Login@index');
        Route::post('/login/check', 'Login@check');

        Route::middleware('auth', function () {
            //@Route Dashboard
            Route::get('/dash', 'Dash@index');
            Route::get('/logout', 'Logout@index');
        });
    });
});
