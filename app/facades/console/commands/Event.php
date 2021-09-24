<?php

namespace App\Facades\Console\Commands;

use App\Facades\Console\Command;

class Event extends Command
{
	public static string $name = 'app:make:event';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function execute()
	{
		$name = $this->input('Please set name for event');
		$this->putFile('/app/events', $name.'Event', $this->getFile('event'));
	}
}
