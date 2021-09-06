<?php

namespace App\Rules;

interface ValidatorInterface
{
	function getRule(array $optional =[]): array;
}
