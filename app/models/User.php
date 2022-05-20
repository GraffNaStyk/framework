<?php

namespace App\Models;

use App\Attributes\Table\Column;
use App\Attributes\Table\Connection;
use App\Attributes\Table\Table;
use App\Facades\Db\Model;

#[Table(table: 'users', isTriggered: true)]
#[Connection(connection: 'default')]
class User extends Model
{
	#[Column(name: 'name')]
	protected string $testNameChange;
	
	#[Column(name: 'password')]
	protected string $testPasswordChange;
	
	public function getTestNameChange(): string
	{
		return $this->testNameChange;
	}
	
	public function setTestNameChange(string $testNameChange): void
	{
		$this->testNameChange = $testNameChange;
	}
	
	public function getTestPasswordChange(): string
	{
		return $this->testPasswordChange;
	}
	
	public function setTestPasswordChange(string $testPasswordChange): void
	{
		$this->testPasswordChange = $testPasswordChange;
	}
}
