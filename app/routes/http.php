<?php

use App\Facades\Http\Router\Route;

Route::when('/admin', '/dash');

Route::namespace('App\Controllers\Http', function () {
    Route::get('/', 'Index@index');
    Route::post('/find', 'Index@find');
});

Route::namespace('App\Controllers\Storage', function() {
	Route::get('/storage/{hash}', 'Storage@display');
	Route::get('/storage', 'Storage@display');
});

Route::alias('/admin', function () {
    Route::namespace('App\Controllers\Admin', function () {
        //@Route login
        Route::get('/login', 'Login@index');
        Route::post('/login/check', 'Login@check');

        Route::middleware('auth', function () {
            //@Route Dashboard
            Route::get('/dash', 'Dash@index');
            Route::post('/dash/upload', 'Dash@upload');
            Route::get('/logout', 'Logout@index');

            //@Route clients
            Route::group(
                [
                    '/clients',
                    '/clients/page',
                    '/clients/page/{page}'
                ],
                'Clients@index',
                'get',
                4,
                ['example']
            );

            Route::post('/clients/store', 'Clients@store');
            Route::get('/clients/add', 'Clients@add');
            Route::get('/clients/edit/{id}', 'Clients@edit');
            Route::get('/clients/show/{id}', 'Clients@show');
            Route::post('/clients/delete', 'Clients@delete');

            Route::get('/users/add', 'Users@add');

            //@Route password reset
            Route::get('/password', 'Password@index');
            Route::post('/password/store', 'Password@store');
            
            Route::get('/test', 'Clients@testFunc');
            
            Route::get('/houses', 'Houses@index');
            Route::get('/houses/add', 'Houses@add');
            Route::post('/houses/store', 'Houses@store');
        });
    });
});
