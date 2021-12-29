<?php

namespace App\Facades\Validator\Rules;

class Min extends Rule
{
	private int $min;
	
	public function __construct(int $min, string $description)
	{
		$this->min         = $min;
		$this->description = $description;
	}

	public function run(): bool
	{
		return $this->field >= $this->min;
	}
}
