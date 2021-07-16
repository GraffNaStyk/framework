<?php

namespace App\Controllers\Api;

use App\Core\BaseController;

class ExampleController extends BaseController
{
    public function index(): ?string
    {
        return var_dump(API);
    }
}
