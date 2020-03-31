<?php namespace App\Controllers\Admin;

use App\Core\AppController;
use App\Core\Auth;
use App\Core\View;

class DashController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        View::set(['title' => 'Panel Administracyjny']);
        $this->loadForAdmin();
        View::setLayout('admin');
        Auth::guard();
    }

    public function index()
    {
        return View::render();
    }

    public function socket()
    {

    }
}
