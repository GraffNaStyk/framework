<?php

namespace App\Services;


use App\Facades\Http\Request;

abstract class AbstractService
{
	public Request $request;
	
	public function setRequest(Request $request): void
	{
		$this->request = $request;
	}
	
	abstract function boot();
}
