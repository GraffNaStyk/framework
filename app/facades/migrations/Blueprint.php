<?php
namespace App\Facades\Migrations;

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
    protected array $queries = [];
    protected bool $store = false;
    
    protected array $length = [
        'tinyint' => '(2)',
        'smallint' => '(4)',
        'mediumint' => '(8)',
        'int' => '(10)',
        'char' => '(10)',
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
    protected array $foreign = [];
    protected object $db;
    protected array $trigger = [];
    
    use TableSystem;
    
    public function __construct($model, $store=false)
    {
        $this->db = new Db($model);
        $this->table = $this->db->table;
        $this->store = $store;
    }
    
    private function lastKey()
    {
        return array_keys($this->tableFields)[count($this->tableFields)-1];
    }
    
    public function generate($name, $fnName, $length = null)
    {
        $this->lastCalled = $fnName;
        $this->currentFieldName = '`'.$name.'`';
        $this->tableFields[] = '`'.$name.'`' . ' ' . $this->lastCalled . ' ' .($length ? '(' . $length . ')' : $this->length[$this->lastCalled]). ' ' . $this->notNull;
        $this->currentKey = $this->lastKey();
    }
    
    public function run()
    {
        if(!empty($this->tableFields)) {
            $fields = implode(', ', $this->tableFields);
            $fields = rtrim($fields, ',');
            $this->otherImplementation = rtrim($this->otherImplementation, ', ');
            $this->sql = $this->startSql . '`' . trim($this->table) . '`' . ' ( ' . $fields . ', ' . $this->otherImplementation . $this->endSql;
            
            if($this->store === true) {
                $this->storeMigration();
                return true;
            } else {
                $this->db->query($this->sql);
            }
        }
        
        if ($this->store === false) {
            if(!empty($this->queries)) {
                foreach ($this->queries as $query)
                    $this->db->query($query);
            }
            
            if(!empty($this->alter)) {
                foreach ($this->alter as $alter)
                    $this->db->query($alter);
            }
            
            if(!empty($this->foreign)) {
                foreach ($this->foreign as $foreign)
                    $this->db->query($foreign);
            }
            
            if(!empty($this->trigger)) {
                foreach ($this->trigger as $trigger)
                    $this->db->query($trigger);
            }
        }
    }
    
    public function clear()
    {
        if ($this->hasTable($this->table) === true) {
            $this->db->query('DROP TABLE ' . $this->table);
        }
    
        $triggers = $this->db->query('SELECT * FROM `INFORMATION_SCHEMA`.`TRIGGERS` WHERE TRIGGER_SCHEMA = "'.$this->db->getDbName().'"');
        foreach ($triggers as $trigger) {
            $this->db->query('DROP TRIGGER '.$trigger['TRIGGER_NAME']);
        }
    }
    
    protected function storeMigration()
    {
        $name = 'dump_'.date('Y_m_d__H_i').'.sql';
        file_put_contents(app_path('app/db/migrate/'.$name), $this->sql.';'.PHP_EOL.PHP_EOL, FILE_APPEND);
        
        if(!empty($this->queries)) {
            foreach ($this->queries as $query)
                file_put_contents(app_path('app/db/migrate/'.$name), $query.';'.PHP_EOL, FILE_APPEND);
        }
        
        if(!empty($this->alter)) {
            foreach ($this->alter as $alter)
                file_put_contents(app_path('app/db/migrate/'.$name), $alter.';'.PHP_EOL, FILE_APPEND);
        }
        
        if(!empty($this->foreign)) {
            foreach ($this->foreign as $foreign)
                file_put_contents(app_path('app/db/migrate/'.$name), $foreign.';'.PHP_EOL, FILE_APPEND);
        }
        
        if(!empty($this->trigger)) {
            foreach ($this->trigger as $trigger)
                file_put_contents(app_path('app/db/migrate/'.$name), $trigger.';'.PHP_EOL, FILE_APPEND);
        }
    }
}
