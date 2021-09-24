<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Trigger extends Command
{
	public static string $name = 'app:make:trigger';
	
	public function __construct()
	{
		parent::__construct();
	}

	public function execute()
	{
		$name = $this->input('Please set name for trigger');
		$this->putFile('/app/triggers', $name.'Trigger', $this->getFile('trigger'));
	}
}
