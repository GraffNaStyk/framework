<?php

namespace App\Facades\Validator\Rules;

class MinLength extends Rule
{
	private int $length;
	
	public function __construct(int $length, string $description)
	{
		$this->length      = $length;
		$this->description = $description;
	}
	
	public function run(): bool
	{
		return strlen($this->field) >= $this->length;
	}
}
