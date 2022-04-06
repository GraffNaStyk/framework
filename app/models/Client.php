<?php

namespace App\Models;

use App\Attributes\Table\Column;
use App\Attributes\Table\Table;
use App\Facades\Db\Model;

#[Table(table: 'clients', isTriggered: false)]
class Client extends Model
{
	#[Column(name: 'ftp_server')]
	private string $eftepeServer;
	private int $id;
	private string $name;
	private string $www;
	
	/**
	 * @return string
	 */
	public function geeftepeServer(): string
	{
		return $this->eftepeServer;
	}
	
	/**
	 * @param string $ftpServer
	 */
	public function seteftepeServer(string $ftpServer): void
	{
		$this->eftepeServer = $ftpServer;
	}
	
	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}
	
	/**
	 * @return string
	 */
	public function getWww(): string
	{
		return $this->www;
	}
	
	/**
	 * @param string $www
	 */
	public function setWww(string $www): void
	{
		$this->www = $www;
	}
	
}
