<?php namespace App\Facades\Migrations;

use App\Db\Db;

class Blueprint
{
    protected string $startSql = 'CREATE TABLE IF NOT EXISTS ';
    protected string $endSql = ' )';
    protected string $sql;
    protected string $table;
    protected string $notNull = ' NOT NULL';
    protected array $tableFields = [];
    protected array $alter = [];
    
    protected array $length = [
        'tinyint' => '(2)',
        'smallint' => '(4)',
        'mediumint' => '(8)',
        'int' => '(10)',
        'char' => '(255)',
        'varchar' => '(100)',
        'boolean' => '(255)',
        'tinytext' => '(50)',
        'text' => '',
        'mediumText' => '',
        'longText' => '',
        'timestamp' => ' '
    ];
    
    protected string $currentKey = '';
    protected string $currentFieldName = '';
    protected string $lastCalled;
    protected $otherImplementation;
    protected string $foreign = '';
    protected object $db;
    protected array $trigger = [];
    
    public function __construct($model)
    {
        $this->db = new Db($model);
        $this->table = $this->db->table;
    }
    
    private function lastKey()
    {
        return array_keys($this->tableFields)[count($this->tableFields)-1];
    }
    
    public function generate($name, $fnName, $length = null)
    {
        $this->lastCalled = $fnName;
        $this->currentFieldName = $name;
        $this->tableFields[] = $name . ' ' . $this->lastCalled .($length ? '(' . $length . ')' : $this->length[$this->lastCalled]). $this->notNull;
        $this->currentKey = $this->lastKey();
    }
    
    public function run()
    {
        if(!empty($this->tableFields)) {
            $fields = implode(', ', $this->tableFields);
            $fields = rtrim($fields, ',');
            $this->otherImplementation = rtrim($this->otherImplementation, ', ');
            $this->sql = $this->startSql . '`' . trim($this->table) . '`' . ' ( ' . $fields . ', ' . $this->otherImplementation . $this->endSql;
    
            $this->db->query($this->sql);
            $this->foreign ?? $this->model->query($this->foreign);
        }
        
        if(!empty($this->trigger)) {
            foreach ($this->trigger as $trigger)
                $this->db->query($trigger);
        }
    
        if(!empty($this->alter)) {
            foreach ($this->alter as $alter)
                $this->db->query($alter);
        }
    }
    
    public function clear()
    {
        $this->db->query('DROP TABLE ' . $this->table);
    }
}
