<?php

use App\Facades\Http\Router;

Router::group(['prefix' => 'admin', 'as' => 'App.Controllers.Admin', 'base' => 'login'], function () {

});

Router::group(['prefix' => 'threads', 'as' => 'App.Controllers.Threads', 'base' => 'Index'], function () {
    Router::get('dupcia', 'Index@index');
});

Router::run();
