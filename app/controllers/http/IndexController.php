<?php

namespace App\Controllers\Http;

use App\Core\Controller;
use App\Model\Item;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        pd(Item::count('name')->exec());
    }

    public function index()
    {
        return $this->render();
    }
}
