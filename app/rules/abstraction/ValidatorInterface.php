<?php

namespace App\Rules\Abstraction;

interface ValidatorInterface
{
	function getRule(array $optional = []): array;
}
