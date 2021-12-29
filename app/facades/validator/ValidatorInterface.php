<?php

namespace App\Facades\Validator;

interface ValidatorInterface
{
	public static function validate(array $request, array $rules): bool;
}
