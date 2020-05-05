<?php namespace App\Controllers\Http;

use App\Core\AppController;
use App\Core\View;
use App\Db\Model;

class IndexController extends AppController
{
    public function __construct()
    {
        View::set([
            'title' => 'Graff Design - Strona Główna',
            'styles' => Model::table('config')->select(['maincolor', 'footercolor', 'textcolor', 'headercolor', 'bgcolor'])->where(['id', '=', 1])->findOrFail()
        ]);
        parent::__construct();
    }

    public function index()
    {
        return View::render();
    }
}
