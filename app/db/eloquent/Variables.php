<?php

namespace App\Db\Eloquent;

trait Variables
{
	protected ?array $data = [];
	
    private string $query;
    
    public string $table;
    
    public bool $onDuplicate = false;
    
    private bool $isFirstWhere = false;
    
    private bool $distinct = false;
    
    private bool $startBracket = false;
	
	private bool $isUpdate = false;
}
