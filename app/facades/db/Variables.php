<?php

namespace App\Facades\Db;

trait Variables
{
    protected ?array $data = [];

    protected ?array $duplicated = [];

    private ?string $query = null;

    public string $table;

    public bool $onDuplicate = false;

    private bool $isFirstWhere = false;

    private bool $distinct = false;

    private bool $startBracket = false;

    private bool $isUpdate = false;

    private bool $first = false;

    private bool $debug = false;

    private bool $selectGroup = false;

    private bool $multiple = false;

    private bool $hasTrigger = false;

    private ?string $triggerMethod;

    private int $startBracketCount = 0;
	
	private ?string $model;
}
