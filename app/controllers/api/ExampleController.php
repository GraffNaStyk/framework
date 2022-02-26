<?php

namespace App\Controllers\Api;

use App\Facades\Http\AbstractController;
use App\Facades\Http\Request;
use App\Facades\Http\Response;

class ExampleController extends AbstractController
{
    public function index(Request $request): ?Response
    {
    	if ($request->isJson()) {
		    dd($request->all());
	    }
    	
        return null;
    }
}
