<?php namespace App\Db;

use App\Db\Eloquent\Builder;
use App\Db\Eloquent\Handle;
use PDO;

class Db extends Builder
{
    private static array $env;
    
    private static ?object $db = null;
    
    private bool $debug = false;
    
    private bool $first = false;
    
    private static string $dbName;

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public static function init(array $env)
    {
        self::$env = $env;
        self::connect();
    }

    private static function connect()
    {
        if (empty(self::$env) === false) {
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
        return false;
    }
    
    public function getDbName()
    {
        return self::$dbName;
    }

    public function select($values = '*')
    {
        $this->values = $values;
        return $this;
    }
    
    public function where(array $where)
    {
        if(empty($where) === true)
            $where = [1,'=',1];
        
        if(is_array($where[0])) {
            foreach ($where as $key => $value) {
                $this->push('where', $value[0], $value[1], $value[2], $value[3] ?? 'AND');
            }
        } else {
            $this->push('where', $where[0], $where[1], $where[2], $where[3] ?? 'AND');
        }
        
        return $this;
    }
    
    public function orWhere(array $where)
    {
        if(isset($where[3]) === false) {
            $where[3] = 'OR';
        }
        
        $this->where($where);
        return $this;
    }

    public function order(string $by, string $type = null)
    {
        $this->order['by'] = $by;
        $this->order['type'] = $type ?? 'ASC';
        return $this;
    }

    public function group(string $group)
    {
        $this->group = $group;
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function limit($limit = null)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function first()
    {
        $this->first = true;
    }

    public function get()
    {
        $this->query = "SELECT ";

        if ($this->distinct)
            $this->query .= $this->buildDistinct();

        $this->query .= $this->prepareValues();
        $this->query .= " FROM `{$this->table}`";

        if (!empty($this->innerJoin['field']))
            $this->query .= $this->buildJoin('innerJoin');

        if (!empty($this->leftJoin['field']))
            $this->query .= $this->buildJoin('leftJoin');

        if (!empty($this->rightJoin['field']))
            $this->query .= $this->buildJoin('rightJoin');

        if (!empty($this->where))
            $this->query .= $this->buildWhereQuery();

        if (!empty($this->group))
            $this->query .= $this->buildGroup();

        if (!empty($this->order['by']))
            $this->query .= $this->buildOrder();

        if (!empty($this->limit))
            $this->query .= " LIMIT {$this->limit}";

        if($this->debug)
            $this->develop();

        return $this->execute();
    }

    public function all()
    {
        $this->query = "SELECT * FROM  `{$this->table}`";
        return $this->execute();
    }
    
    public function count($data = [])
    {
        $field = $data[0] ?? '*';
        $alias = $data[1] ?? 'total';
        $this->query = "SELECT count($field) as '$alias' from `{$this->table}` {$this->buildWhereQuery()}";
        return $this->execute()[0];
    }

    public function increment($column, $value)
    {
        $this->query = "UPDATE `{$this->table}` SET {$column} = {$column} + {$value} {$this->buildWhereQuery()}";

        return $this->execute();
    }

    public function decrement($column, $value)
    {
        $this->query = "UPDATE `{$this->table}` SET {$column} = {$column} - {$value} {$this->buildWhereQuery()}";

        return $this->execute();
    }

    public function join(array $join)
    {
        $this->push('innerJoin', $join[1], $join[2], $join[3], 'INNER JOIN', $join[0]);
        return $this;
    }

    public function leftJoin(array $join)
    {
        $this->push('leftJoin', $join[1], $join[2], $join[3], 'LEFT JOIN', $join[0]);
        return $this;
    }

    public function rightJoin(array $join)
    {
        $this->push('rightJoin', $join[1], $join[2], $join[3], 'RIGHT JOIN', $join[0]);
        return $this;
    }
    
    private function execute()
    {
        $this->setData();
        if (preg_match('/^(INSERT|UPDATE|DELETE)/', $this->query)) {
            try {
                if (self::$db->prepare($this->query)->execute($this->data)) {
                    return true;
                }
            } catch (\PDOException $e) {
                Handle::throwException($e, $this->query);
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
                Handle::throwException($e, $this->query);
            }
        }
        return false;
    }

    public function lastId()
    {
        return self::$db->lastInsertId();
    }

    public function update(array $data)
    {
        $this->data = $data;
        $this->buildUpdateQuery();

        if($this->execute())
            return true;

        return false;
    }

    public function insert(array $data)
    {
        $this->data = $data;
        $this->buildSaveQuery();

        if($this->execute())
            return true;

        return false;
    }
    
    public function delete()
    {
        $this->query = "DELETE FROM `{$this->table}` {$this->buildWhereQuery()}";
        
        if($this->execute())
            return true;
        
        return false;
    }

    public function findOrFail()
    {
        $result = $this->get();

        if(empty($result) === true)
            return false;

        if(isset($result[1]))
            return false;

        if(isset($result[0]))
            return $result[0];

        return false;
    }

    public function debug()
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
    
    private function develop()
    {
        $statement = $this->query;
        foreach ($this->data as $key => $item) {
            $statement = str_replace(':'.$key, "'".$item."'", $statement);
        }
        
        pd([
            'Query' => $statement,
            'RawQuery' => $this->query,
            'Data' => $this->data,
            'Where' => $this->where,
            'Join' => [
                'Left' => $this->leftJoin,
                'Right' => $this->rightJoin,
                'Inner' => $this->innerJoin,
            ]
        ]);
    }
}
