<?php

namespace App\Facades\Http;

abstract class AbstractService
{
	public Request $request;
	
	public function setRequest(Request $request): void
	{
		$this->request = $request;
	}
	
	abstract function boot();
}
