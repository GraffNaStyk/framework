<?php

namespace App\Db\Eloquent;

trait Variables
{
	protected ?array $data = [];
	
    private ?string $query = null;
    
    public string $table;
    
    public bool $onDuplicate = false;
    
    private bool $isFirstWhere = false;
    
    private bool $distinct = false;
    
    private bool $startBracket = false;
	
	private bool $isUpdate = false;
	
	private bool $first = false;
	
	private bool $debug = false;
}
