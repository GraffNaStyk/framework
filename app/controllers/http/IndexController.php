<?php namespace App\Controllers\Http;

use App\Core\Controller;
use App\Facades\Http\View;
use App\Model\Config;

class IndexController extends Controller
{
    public function __construct()
    {
        View::set([
            'title' => 'Graff Design - Strona Główna',
            'styles' => Config::select(['maincolor', 'footercolor', 'textcolor', 'headercolor', 'bgcolor'])
                ->where(['id', '=', 1])
                ->findOrFail()
        ]);
        parent::__construct();
    }

    public function index()
    {
        return View::render();
    }
}
