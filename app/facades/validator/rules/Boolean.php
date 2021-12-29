<?php

namespace App\Facades\Validator\Rules;

class Boolean extends Rule
{
	public function __construct(string $description)
	{
		$this->description = $description;
	}
	
	public function run(): bool
	{
		return ($this->field === true || $this->field === false || $this->field === 1 || $this->field === 0);
	}
}
