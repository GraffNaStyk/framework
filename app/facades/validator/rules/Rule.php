<?php

namespace App\Facades\Validator\Rules;

abstract class Rule
{
	protected $field;
	
	protected string $description;

	public abstract function run(): bool;
	
	public function setField($field)
	{
		$this->field = $field;
	}
	
	public function getErrorMessage(): string
	{
		return $this->description;
	}
}
