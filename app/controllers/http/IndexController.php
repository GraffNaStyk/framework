<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return $this->render();
    }
}
