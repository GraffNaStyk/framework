<?php namespace App\Controllers\Http;

use App\Core\AppController;
use App\Core\View;
use App\Model\User;

class IndexController extends AppController
{
    public function __construct()
    {
        View::set(['title' => 'Strona Główna']);
        $this->loadForPage();
        parent::__construct();
    }

    public function index()
    {
        return View::render();
    }

    public function test()
    {
        return View::render();
    }

    public function http404()
    {
        return View::render();
    }
}
