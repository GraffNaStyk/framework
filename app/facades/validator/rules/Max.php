<?php

namespace App\Facades\Validator\Rules;

class Max extends Rule
{
	private int $max;
	
	public function __construct(int $max, string $description)
	{
		$this->max         = $max;
		$this->description = $description;
	}

	public function run(): bool
	{
		return $this->field <= $this->max;
	}
}
