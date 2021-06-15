<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;

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