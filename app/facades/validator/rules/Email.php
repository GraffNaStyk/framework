<?php

namespace App\Facades\Validator\Rules;

class Email extends Rule
{
	public function __construct(string $description)
	{
		$this->description = $description;
	}
	
	public function run(): bool
	{
		return filter_var($this->field, FILTER_VALIDATE_EMAIL);
	}
}
