<?php

use \App\Core\Router;

Router::defaultController();

Router::get('Index', 'dupa');
Router::post('Example/delete', 'usun-postac');

Router::run();
