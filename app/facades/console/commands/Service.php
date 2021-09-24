<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Service extends Command
{
	public static string $name = 'app:make:service';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for service');
		$this->putFile('/app/services', $name.'Service', $this->getFile('service'));
	}
}
