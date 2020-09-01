<?php
namespace App\Db\Eloquent;

trait Variables
{
    protected array $where = ['field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    
    protected $values = '*';
    
    public string $table;
    
    protected string $query;
    
    protected array $order = ['by' => '', 'type' => 'ASC'];
    
    protected string $limit = '';
    
    protected string $group = '';
    
    protected bool $distinct = false;
    
    protected array $innerJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    
    protected array $leftJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    
    protected array $rightJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    
    protected ?array $data = null;
    
    private array $specialVariables = ['CURDATE()'];
    
    private static array $env;
    
    private static ?object $db = null;
    
    private bool $debug = false;
    
    private bool $first = false;
    
    private static string $dbName;
    
    public bool $hasId = false;
    
    protected function reconstruct($model)
    {
        $this->where = ['field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
        $this->values = '*';
        $this->query;
        $this->order = ['by' => '', 'type' => 'ASC'];
        $this->limit = '';
        $this->group = '';
        $this->distinct = false;
        $this->innerJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
        $this->leftJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
        $this->rightJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
        $this->data = [];
        $this->table = $model::$table;
    }
}
