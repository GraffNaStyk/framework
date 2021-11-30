<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Response;
use App\Models\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user): Response
    {
        return $this->render();
    }
}
