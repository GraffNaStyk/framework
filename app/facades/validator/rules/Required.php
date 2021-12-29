<?php

namespace App\Facades\Validator\Rules;

class Required extends Rule
{
	public function __construct(string $description)
	{
		$this->description = $description;
	}
	
	public function run(): bool
	{
		return isset($this->field);
	}
}
