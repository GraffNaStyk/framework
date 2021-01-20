<?php

use App\Facades\Http\Router;
use App\Facades\Http\Route;

Route::when('/admin', '/dash');

Route::prefix('/admin', function () {
    Route::namespace('App\Controllers\Admin', function () {
        Route::get('/login', 'Login@index');
        Route::post('/login/check', 'Login@check');
        
        Route::middleware('auth', function () {
            //@Route Dashboard
            Route::get('/dash', 'Dash@index');
            Route::get('/logout', 'Logout@index');
            
            //@Route users
            Route::get('/users/{name}', 'Dash@users');
            
            //@Route clients
            Route::get('/clients', 'Clients@index');
            Route::get('/clients/add', 'Clients@add');
            Route::post('/clients/store', 'Clients@store');
        });
    });
});

Route::namespace('App\Controllers\Http', function () {
    Route::get('/', 'Index@index');
});

Router::run();
