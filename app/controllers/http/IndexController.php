<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Storage\Storage;
use App\Models\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user, Request $request, Storage $storage): Response
    {
        return $this->render();
    }
}
