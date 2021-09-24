<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Repository extends Command
{
	public static string $name = 'app:make:repository';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for repository');
		$this->putFile('/app/repository', $name.'Repository', $this->getFile('repository'));
	}
}
