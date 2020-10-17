<?php

use App\Facades\Dispatcher\Dispatcher;
use App\Facades\Migrations\Migration;

require_once __DIR__.'/index.php';

$job = Dispatcher::dispatch($argv);

$job->register(['make', 'up', 'down', 'dump']);

$migration = new Migration($job->getArgs());

if ($job->do('make') === true) {
    $migration->make();
}

if ($job->do('up') === true) {
    $migration->up();
}

if ($job->do('down') === true) {
    $migration->down();
}

if ($job->do('dump') === true) {
    $migration->dump();
}

$job->end();
