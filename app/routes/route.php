<?php

use App\Facades\Http\Router;
use App\Facades\Http\Route;

Route::when('/admin', '/dash');

Route::prefix('/admin', function () {
    Route::namespace('App\Controllers\Admin', function () {
        //@Route login and logout
        Route::get('/login', 'Login@index');
        Route::post('/login/check', 'Login@check');
        
        Route::middleware('auth', function () {
            //@Route Dashboard
            Route::get('/dash', 'Dash@index');
            Route::post('/dash/upload', 'Dash@upload');
            Route::get('/logout', 'Logout@index');
            
            //@Route users
            Route::get('/users/{name}', 'Dash@users');
            
            //@Route clients
            Route::get('/clients', 'Clients@index', 4, 'example');
            Route::get('/clients/add', 'Clients@add');
            Route::post('/clients/store', 'Clients@store');
            
            //@Route password reset
            Route::get('/password', 'Password@index');
            Route::post('/password/store', 'Password@store');
        });
    });
});

Route::namespace('App\Controllers\Http', function () {
    Route::get('/', 'Index@index');
});

Router::run();
