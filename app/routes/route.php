<?php
use App\Facades\Http\Router;

Router::group(['prefix' => 'admin', 'as' => 'App\Controllers\Admin', 'base' => 'login'], function () {
    Router::get('authorize', 'Dash@index', 1);
    Router::get('/clients/add/{id}', 'Clients@add', 1);
    Router::get('/clients', 'Clients@index', 1);
});

Router::run();
