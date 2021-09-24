<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Middleware extends Command
{
	public static string $name = 'app:make:middleware';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for middleware');
		$this->putFile('/app/controllers/middleware', $name, $this->getFile('middleware'));
	}
}
