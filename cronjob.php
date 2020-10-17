<?php

use \App\Facades\Dispatcher\Dispatcher;

require_once __DIR__.'/index.php';

$job = Dispatcher::dispatch($argv);

$job->end();
