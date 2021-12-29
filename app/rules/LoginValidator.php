<?php

namespace App\Rules;

use App\Facades\Validator\Rules\Required;
use App\Facades\Validator\Rules\Text;

class LoginValidator
{
	public function getRules(): array
	{
		return [
			'name' => [
				new Text('a'),
				new Required('Required')
			],
			'password' => [
				new Text('String'),
				new Required('Required')
			]
		];
	}
}
