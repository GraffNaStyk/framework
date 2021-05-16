<?php

use App\Facades\Http\Router\Route;
use App\Facades\Http\Router\Router;

Route::when('/admin', '/dash');

Route::namespace('App\Controllers\Http', function () {
	Route::get('/', 'Index@index');
});

Route::alias('/admin', function () {
	Route::namespace('App\Controllers\Admin', function () {
		//@Route login
		Route::get('/login', 'Login@index')->middleware(['isLogged']);
		Route::post('/login/check', 'Login@check');
		
		Route::middleware('auth', function () {
			//@Route Dashboard
			Route::get('/dash', 'Dash@index');
			Route::post('/dash/upload', 'Dash@upload');
			Route::get('/logout', 'Logout@index');
			
			//@Route users
			Route::get('/users/{name}', 'Dash@users');
	
			//@Route clients
			Route::post('/clients/store', 'Clients@store');
			Route::get('/clients/add', 'Clients@add');
			Route::get('/clients', 'Clients@index')->middleware(['example']);
			Route::get('/clients/{page}', 'Clients@index')->middleware(['example']);
			Route::get('/clients/edit/{id}', 'Clients@edit');
			Route::get('/clients/details/{id}', 'Clients@show');

			//@Route password reset
			Route::get('/password', 'Password@index');
			Route::post('/password/store', 'Password@store');
		});
	});
});

(new Router());
