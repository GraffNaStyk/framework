<?php

namespace App\Db;

use App\Db\Eloquent\Builder;
use App\Db\Eloquent\Handle;
use App\Db\Eloquent\Variables;
use PDO;

class Db
{
    use Variables;
    use Builder;
    
    private static array $env;
    private static ?object $db = null;
    private static ?string $dbName = null;
    public ?string $as = null;
    
    public function __construct($model)
    {
        $this->table = $model::$table;
    }

    public static function init(array $env)
    {
        self::$env = $env;
        self::connect();
    }
    
    private static function connect(): void
    {
        if (! empty(self::$env)) {
            try {
                if (self::$db === null) {
                    self::$db = new PDO('mysql:host=' . self::$env['host'] . ';dbname=' . self::$env['dbname'], self::$env['user'], self::$env['pass']);
                    self::$dbName = self::$env['dbname'];
                    self::$env = [];
                    self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$db->query('set names utf8;');
                }
            } catch (\PDOException $e) {
                trigger_error("Database connection error");
                Handle::throwException($e, 'DATABASE CONNECTION ERROR');
            }
        }
    }

    public function getDbName(): string
    {
        return self::$dbName;
    }
    
    public function as(string $alias): Db
    {
        $this->as = ' as `'.$alias.'`';
        
        return $this;
    }
    
    public function distinct(): Db
    {
        $this->distinct = true;
        
        return $this;
    }
    
    public function duplicate(): Db
    {
        $this->onDuplicate = true;
        
        return $this;
    }
    
    public function insert(array $values): bool
    {
        $this->data = $values;
        $this->query = "INSERT INTO `{$this->table}` (";
    
        foreach ($this->data as $key => $field) {
            $this->query .= "`{$key}`, ";
        }
    
        $this->query = rtrim($this->query, ', ') .") VALUES (";
    
        foreach ($this->data as $key => $field) {
            $this->query .= ":$key, ";
        }
    
        $this->query = rtrim($this->query, ', ') .")";
    
        if ($this->onDuplicate === true) {
            $this->query .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($this->data as $key => $field) {
                $this->query .= "`{$key}` = :{$key}, ";
            }
        }
    
        $this->query = rtrim($this->query, ', ');
    
        return $this->execute();
    }

    public function select(array $values = []): Db
    {
        $this->query = 'SELECT';
        
        if ($this->distinct) {
            $this->query .= ' DISTINCT';
        }
        
        if (empty($values)) {
            $this->query .= ' * FROM `'.$this->table.'`'.$this->as;
        } else {
            $this->query .= " {$this->prepareValuesForSelect($values)} FROM `{$this->table}`".$this->as;
        }
        
        return $this;
    }
    
    public function update(array $values): Db
    {
        $this->data = $values;
        $this->query = "UPDATE `{$this->table}` SET ";
    
        foreach ($this->data as $key => $value) {
            if ((string) $key === 'id') {
                continue;
            }
            $this->query .= "`{$key}` = :{$key}, ";
        }
    
        $this->query = rtrim($this->query, ', ');
        
        return $this;
    }
    
    public function delete(): Db
    {
        $this->query = "DELETE FROM `{$this->table}`";
        
        return $this;
    }

    public function where(string $item, string $is, string $item2): Db
    {
        $tmpItem = str_replace('.', '__', $item).'__'.rand(1,10000);
        $this->data[$tmpItem] = $item2;
        
        if ($this->isFirstWhere === true) {
            $this->query .= " AND {$this->prepareValueForWhere($item)} {$is} :{$tmpItem} ";
        } else {
            $this->isFirstWhere = true;
            $this->query .= " WHERE {$this->prepareValueForWhere($item)} {$is} :{$tmpItem}";
        }
        
        return $this;
    }

    public function orWhere(string $item, string $is, string $item2): Db
    {
        $tmpItem = str_replace('.', '__', $item).'__'.rand(1,10000);
        $this->data[$tmpItem] = $item2;
        $this->query .= " OR {$this->prepareValueForWhere($item)} {$is} :{$tmpItem} ";
        
        return $this;
    }
    
    public function whereNull(string $item): Db
    {
        if ($this->isFirstWhere === true) {
            $this->query .= " AND {$this->prepareValueForWhere($item)} IS NULL ";
        } else {
            $this->isFirstWhere = true;
            $this->query .= " WHERE {$this->prepareValueForWhere($item)} IS NULL ";
        }
    
        return $this;
    }
    
    public function whereNotNull(string $item): Db
    {
        if ($this->isFirstWhere === true) {
            $this->query .= " AND {$this->prepareValueForWhere($item)} IS NOT NULL ";
        } else {
            $this->isFirstWhere = true;
            $this->query .= " WHERE {$this->prepareValueForWhere($item)} IS NOT NULL ";
        }
    
        return $this;
    }
    
    public function orWhereNull(string $item): Db
    {
        $this->query .= " OR {$this->prepareValueForWhere($item)} IS NULL ";
    
        return $this;
    }
    
    public function orWhereNotNull(string $item): Db
    {
        $this->query .= " OR {$this->prepareValueForWhere($item)} IS NOT NULL ";
    
        return $this;
    }
    
    public function whereIn(array $items, string $item): Db
    {
        $items = "'".implode("', '", $items)."'";

        if ($this->isFirstWhere === true) {
            $this->query .= " AND {$this->prepareValueForWhere($item)} IN ({$items}) ";
        } else {
            $this->isFirstWhere = true;
            $this->query .= " WHERE {$this->prepareValueForWhere($item)} IN ({$items}) ";
        }
        
        return $this;
    }
    
    public function whereNotIn(array $items, string $item): Db
    {
        $items = "'".implode("', '", $items)."'";
    
        if ($this->isFirstWhere === true) {
            $this->query .= " AND {$this->prepareValueForWhere($item)} NOT IN ({$items}) ";
        } else {
            $this->isFirstWhere = true;
            $this->query .= " WHERE {$this->prepareValueForWhere($item)} NOT IN ({$items}) ";
        }
    
        return $this;
    }
    
    public function raw(string $raw): Db
    {
        $this->query .= ' '.$raw;
        
        return $this;
    }
    
    public function order(array $by, string $type = 'ASC'): Db
    {
        $this->query .= " ORDER BY {$this->prepareValuesForSelect($by)} {$type}";
        
        return $this;
    }

    public function group(string $group): Db
    {
        $this->query .= " GROUP BY {$this->prepareValueForWhere($group)} {$type}";
        
        return $this;
    }

    public function limit(int $limit): Db
    {
        $this->query .= " LIMIT {$limit}";
    
        return $this;
    }
    
    public function offset(int $offset): Db
    {
        $this->query .= " OFFSET {$offset}";
    
        return $this;
    }

    public function first()
    {
    }

    public function get(): array
    {
        if ($this->debug) {
            $this->develop();
        }
        
        return $this->execute();
    }

    public function count($data = [])
    {
    }
    
    public function increment(string $field, int $increment): Db
    {
        $this->query = "UPDATE `{$this->table}` SET {$field} = {$field} + {$increment} ";
        
        return $this;
    }
    
    public function decrement(string $field, int $decrement): Db
    {
        $this->query = "UPDATE `{$this->table}` SET {$field} = {$field} - {$decrement} ";
        
        return $this;
    }

    public function join(string $table, string $value1, string $by, string $value2, bool $isRigidly = false): Db
    {
        $this->setJoin('INNER', $table, $value1, $by, $value2, $isRigidly);
        return $this;
    }

    public function leftJoin(string $table, string $value1, string $by, string $value2, bool $isRigidly = false): Db
    {
        $this->setJoin('LEFT', $table, $value1, $by, $value2, $isRigidly);
        return $this;
    }

    public function rightJoin(string $table, string $value1, string $by, string $value2, bool $isRigidly = false): Db
    {
        $this->setJoin('RIGHT', $table, $value1, $by, $value2, $isRigidly);
        return $this;
    }
    
    private function setJoin(string $type, string $table, string $value1, string $by, string $value2, bool $isRigidly = false): void
    {
        if ($isRigidly) {
            $twoValue = $value2;
        } else {
            $twoValue = $this->prepareValueForWhere($value2);
        }
        
        $this->query .= " {$type} JOIN {$this->prepareValueForWhere($table)} ON {$this->prepareValueForWhere($value1)} {$by} {$twoValue}";
    }

    private function execute(): array
    {
        $this->setData();
        
        if (preg_match('/^(INSERT|UPDATE|DELETE)/', $this->query)) {
            try {
                if (self::$db->prepare($this->query)->execute($this->data)) {
                    return true;
                }
            } catch (\PDOException $e) {
                Handle::throwException($e, $this->develop(true));
            }
            return false;
        } else {
            try {
                $stmt = self::$db->prepare($this->query);
                $stmt->execute($this->data);
                
                if ($this->first === true) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                }
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                Handle::throwException($e, $this->develop(true));
            }
        }
        return false;
    }

    public function lastId()
    {
        return self::$db->lastInsertId();
    }

    public function debug(): Db
    {
        $this->debug = true;

        return $this;
    }

    public function query($query)
    {
        $stmt = self::$db->prepare($query);
        $stmt->execute();

        if (preg_match('/^(SELECT)/', $query)) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    private function develop($return = false)
    {
        $statement = $this->query;
        
        foreach ($this->data as $key => $item) {
            $statement = str_replace(':'.$key, "'".$item."'", $statement);
        }
        
        if ($return) {
            return $statement;
        }

        pd($statement);
    }
}
