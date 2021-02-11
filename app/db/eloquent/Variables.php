<?php

namespace App\Db\Eloquent;

trait Variables
{
    private string $query;
    
    public string $table;
    
    public bool $onDuplicate = false;
    
    private bool $isFirstWhere = false;
    
    private bool $distinct = false;
    
    private bool $startBracket = false;
    
    private array $relations = [];
}
