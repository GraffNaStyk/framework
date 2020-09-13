<?php
use App\Facades\Http\Router;

Router::group(['prefix' => 'admin', 'as' => 'App.Controllers.Admin', 'base' => 'login'], function () {

});

Router::run();
