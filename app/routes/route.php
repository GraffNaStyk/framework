<?php

use \App\Core\Router;

Router::group(['prefix' => 'admin', 'as' => 'App.Controllers.Admin', 'base' => 'login'], function () {
    Router::get('dash/index', 'hejcia');
});

Router::get('Contact/index', 'kontakt');
Router::get('Realizations/index', 'realizacje');
Router::get('clients/index', 'klienci');
Router::run();
