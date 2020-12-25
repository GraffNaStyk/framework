<?php
namespace App\Db\Eloquent;

trait Variables
{
    private string $query;
    
    private string $table;
    
    public bool $onDuplicate = false;
    
    private bool $isFirstWhere = false;
    
    private bool $distinct = false;
}
