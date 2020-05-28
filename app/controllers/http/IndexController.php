<?php namespace App\Controllers\Http;

use App\Core\Controller;
use App\Model\Config;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->set([
            'page'=> ['title' => 'Graff Design - Strona główna'],
            'styles' => Config::select(['maincolor', 'footercolor', 'textcolor', 'headercolor', 'bgcolor'])
                ->where(['id', '=', 1])
                ->findOrFail()
        ]);
    }

    public function index()
    {
        return $this->render();
    }
}
