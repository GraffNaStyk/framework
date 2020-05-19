<?php

use \App\Core\Router;

Router::group(['prefix' => 'admin', 'as' => 'App.Controllers.Admin', 'base' => 'login'], function () {
    Router::get('Dash/index', 'eluwina');
    Router::get('Appearance/colors', 'dupa/cycuszki');
});

Router::get('Contact/index', 'kontakt');
Router::get('Realizations/index', 'realizacje');
Router::get('clients/index', 'klienci');
Router::run();
