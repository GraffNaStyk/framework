<?php namespace App\Controllers\Http;

use App\Core\AppController;
use App\Core\View;

class IndexController extends AppController
{
    public function __construct()
    {
        View::set(['title' => 'Graff Design - Strona Główna']);
        parent::__construct();
    }

    public function index()
    {
        return View::render();
    }

    public function http404()
    {
        return View::render();
    }
}
