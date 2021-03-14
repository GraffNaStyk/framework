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
	
	private static array $options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS       => PDO::NULL_EMPTY_STRING,
		PDO::ATTR_CASE               => PDO::CASE_LOWER,
		PDO::ATTR_EMULATE_PREPARES   => false,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8;'
	];
    
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
                    self::$db = new PDO(
                        'mysql:host='.self::$env['DB_HOST'].';dbname='.self::$env['DB_NAME'],
                        self::$env['DB_USER'],
                        self::$env['DB_PASS'],
	                    self::$options
                    );

                    self::$dbName = self::$env['DB_NAME'];
                    self::$env = [];
                }
            } catch (\PDOException $e) {
                trigger_error("Database connection error");
                Handle::throwException($e, 'DATABASE CONNECTION ERROR');
            }
        }
    }
    
    public static function getInstance(): ?PDO
    {
        return self::$db;
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
    
    public function onDuplicate(array $duplicated = []): Db
    {
        $this->onDuplicate = true;
        $this->duplicated = $duplicated;
        
        return $this;
    }
	
	public function selectGroup(array $values = []): Db
    {
    	$this->selectGroup = true;
    	$this->select($values);
    	return $this;
    }
    
    public function insert(array $values): Db
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

            if (! empty($this->duplicated)) {
	            foreach ($this->duplicated as $field) {
		            $this->query .= "`{$field}` = :{$field}, ";
	            }
            } else {
	            foreach ($this->data as $key => $field) {
		            $this->query .= "`{$key}` = :{$key}, ";
	            }
            }
        }
    
        $this->query = rtrim($this->query, ', ');
    
        return $this;
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
	    $this->isUpdate = true;
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
    
    public function exec(): bool
    {
        return $this->execute();
    }

    public function where(string $item, string $is, string $item2): Db
    {
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} {$is} :{$this->setValue($item, $item2)}";
        
        return $this;
    }

    public function orWhere(string $item, string $is, string $item2): Db
    {
        $this->appendToQuery(true);
        $this->query .= "{$this->prepareValueForWhere($item)} {$is} :{$this->setValue($item, $item2)} ";
        
        return $this;
    }
    
    public function whereNull(string $item): Db
    {
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} IS NULL ";
        
        return $this;
    }
    
    public function whereNotNull(string $item): Db
    {
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} IS NOT NULL ";
        
        return $this;
    }
    
    public function orWhereNull(string $item): Db
    {
        $this->appendToQuery(true);
        $this->query .= "{$this->prepareValueForWhere($item)} IS NULL ";
        
        return $this;
    }
    
    public function orWhereNotNull(string $item): Db
    {
        $this->appendToQuery(true);
        $this->query .= "{$this->prepareValueForWhere($item)} IS NOT NULL ";
        
        return $this;
    }
    
    public function whereIn(string $item, array $items): Db
    {
        $items = "'".implode("', '", $items)."'";
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} IN ({$items}) ";
        
        return $this;
    }
    
    public function whereNotIn(string $item, array $items): Db
    {
        $items = "'".implode("', '", $items)."'";
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} NOT IN ({$items}) ";
        
        return $this;
    }
    
    public function whereBetween(string $item, array $items): Db
    {
        $this->appendToQuery();
        $this->query .= "{$this->prepareValueForWhere($item)} BETWEEN
                            :{$this->setValue($item, $items[0])} AND :{$this->setValue($item, $items[1])} ";
        
        return $this;
    }
    
    public function raw(string $raw): Db
    {
        $this->query .= ' '.$raw;
        
        return $this;
    }
    
    public function bind(array $data): Db
    {
    	foreach ($data as $key => $value) {
    		$newK = $this->setValue($key, $value);
		    $this->query = preg_replace('/:'.$key.'/', ':'.$newK, $this->query, 1);
	    }

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
        $this->first = true;
        
        return $this->execute();
    }

    public function get(): array
    {
        return $this->execute();
    }
    
    public function exist()
    {
        $res = $this->first();
        
        if (empty($res)) {
            return false;
        }
        
        return $res;
    }

    public function count(string $item): Db
    {
        $this->query = "SELECT COUNT({$item}) as count from `{$this->table}`";
        $this->first = true;
        
        return $this;
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
    
    private function setJoin(
         string $type,
         string $table,
         string $value1,
         string $by,
         string $value2,
         bool $isRigidly = false
    ): void {
        if ($isRigidly) {
            $twoValue = $value2;
        } else {
            $twoValue = $this->prepareValueForWhere($value2);
        }
        
        $this->query .= " {$type} JOIN {$this->prepareValueForWhere($table)} ON
                            {$this->prepareValueForWhere($value1)} {$by} {$twoValue}";
    }

    private function execute()
    {
        $this->setData();
        
	    if ($this->debug) {
		    $this->develop();
	    }
	    
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
                $pdo = self::$db->prepare($this->query);
                $pdo->execute($this->data);
                
                if ($this->first) {
                    return $pdo->fetch(PDO::FETCH_OBJ);
                }
                
				if ($this->selectGroup) {
					$this->selectGroup = false;
					return $pdo->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_OBJ);
				} else {
					return $pdo->fetchAll(PDO::FETCH_OBJ);
				}

            } catch (\PDOException $e) {
                Handle::throwException($e, $this->develop(true));
            }
        }
        return false;
    }

    public function lastId(): int
    {
        return (int) self::$db->lastInsertId();
    }

    public function debug(): Db
    {
        $this->debug = true;

        return $this;
    }

    public function query($query): ?array
    {
        $stmt = self::$db->prepare($query);
        $stmt->execute();

        if (preg_match('/^(SELECT)/', $query)) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
	
	    return null;
    }

    private function develop($return = false): ?string
    {
        $statement = $this->query;
        
        foreach ($this->data as $key => $item) {
            $statement = str_replace(':'.$key, "'".$item."'", $statement);
        }
        
        if ($return) {
            return $statement;
        }

        pd([
            'query' => $statement,
            'raw'   => $this->query,
            'params' => $this->data
        ]);
        
        return null;
    }
    
    public function getColumnsInfo(): ?array
    {
        return $this->query(
            'SELECT COLUMN_NAME as name, DATA_TYPE as type
                            FROM INFORMATION_SCHEMA.COLUMNS
                                WHERE TABLE_NAME = "'.$this->table.'"'
        );
    }
    
    public function startBracket(): Db
    {
        $this->startBracket = true;
        return $this;
    }
    
    public function endBracket(): Db
    {
        $this->query .= ')';
        return $this;
    }
}
