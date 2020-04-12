<?php

use \App\Core\Router;

Router::get('Contact/index', 'kontakt');
Router::get('Realizations/index', 'realizacje');

Router::run();
