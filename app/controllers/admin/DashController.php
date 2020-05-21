<?php namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\View;

class DashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        View::set(['title' => 'Panel Administracyjny']);
        View::layout('admin');
        Auth::guard();
    }

    public function index()
    {
        return View::render();
    }

    public function modal()
    {
        return View::render();
    }
}
