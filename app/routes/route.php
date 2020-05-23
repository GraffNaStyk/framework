<?php

use App\Facades\Http\Router;

Router::group(['prefix' => 'admin', 'as' => 'App.Controllers.Admin', 'base' => 'login'], function () {
    Router::get('eluwina', 'Dash/index');
    Router::get('ustawienia/{id}/ustaw/{id2}', 'appearance/colors');
});

Router::get('kontakt', 'index/contact');
Router::get('realizacje', 'realizations/index');
Router::get('klienci', 'clients/index');

Router::run();
