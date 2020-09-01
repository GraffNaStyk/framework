<?php
use App\Facades\Migrations\Migration;

if ((string) php_sapi_name() !== 'cli') {
    header('location: index.php');
}

require_once __DIR__.'/index.php';

$migration = Migration::dispatch($argv);

if ($migration->do('make') === true) {
    $migration->make();
    exit();
}

if ($migration->do('up') === true) {
    $migration->up();
    exit();
}

if ($migration->do('down') === true) {
    $migration->down();
    exit();
}

if ($migration->do('dump') === true) {
    $migration->dump();
    exit();
}
