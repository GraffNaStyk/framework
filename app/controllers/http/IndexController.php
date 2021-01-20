<?php

namespace App\Controllers\Http;

use App\Core\Controller;
use App\Facades\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->render();
    }
}
