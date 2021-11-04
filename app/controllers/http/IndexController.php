<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Response;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): Response
    {
        return $this->render();
    }
}
