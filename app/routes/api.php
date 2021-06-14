<?php

use App\Facades\Http\Router\Route;
use App\Facades\Http\Router\Router;

Route::alias('/api', function () {
	Route::namespace('App\Controllers\Api', function () {
		Route::get('/', 'Example@index');
	});
});

(new Router());
