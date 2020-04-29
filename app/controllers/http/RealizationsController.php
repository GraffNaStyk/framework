<?php namespace App\Controllers\Http;

use App\Core\View;
use App\Model\Realization;

class RealizationsController extends IndexController
{
    public function __construct()
    {
        parent::__construct();
        View::set(['title' => 'Graff Design - Realizacje']);
    }

    public function index()
    {
        return View::render([
            'realizations' => Realization::all()
        ]);
    }
}
