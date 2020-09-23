<?php
use App\Facades\Http\Router;

Router::group(['prefix' => 'admin', 'as' => 'App\Controllers\Admin', 'base' => 'login'], function () {
    Router::get('authorize', 'Dash@index', 1);
    Router::get('test', 'Clients@index', 4);
});

Router::run();
