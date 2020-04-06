<?php namespace App\Db;

use App\Db\Eloquent\Builder;
use PDO;

class Db extends Builder
{
    private static $env;
    private static $db;
    private $debug = false;

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
        if (!empty(self::$env['host'])) {
            self::$db = new PDO('mysql:host=' . self::$env['host'] . ';dbname=' . self::$env['dbname'], self::$env['user'], self::$env['pass']);
            self::$env = [];
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->query('set names utf8;');
        }

        return false;
    }

    public function select($values = '*')
    {
        $this->values = $values;
        return $this;
    }

    public function where(array $where, string $type = 'where')
    {
        if(empty($where))
            $where = [1,'=',1];

        $connector = $type == 'where' ? 'AND' : 'OR';
        $this->push('where', $where[0], $where[1], $where[2], $connector);

        return $this;
    }

    public function orWhere(array $where)
    {
        $this->where($where, 'orWhere');
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
            $this->query .= $this->buildWhere();

        if (!empty($this->group))
            $this->query .= $this->buildGroup();

        if (!empty($this->order['by']))
            $this->query .= $this->buildOrder();

        if (!empty($this->limit))
            $this->query .= " LIMIT {$this->limit}";

        if($this->debug)
            $this->develop();

        return $this->formatJoin($this->execute());
    }

    public function all()
    {
        $this->query = "SELECT * FROM  `{$this->table}`";
        return $this->execute();
    }

    public function count($alias)
    {
        $alias = is_array($alias) ? 'total' : $alias;
        $this->query = "SELECT count(*) as '$alias' from `{$this->table}`";
        return $this->execute()[0];
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
        if(strpos($this->query, 'INSERT') !== FALSE || strpos($this->query, 'UPDATE') !== FALSE || strpos($this->query, 'DELETE') !== FALSE) {

            if(self::$db->prepare($this->query)->execute($this->data))
                return true;

            return false;

        } else {
            $stmt = self::$db->query($this->query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }

    public function lastId()
    {
        return self::$db->lastInsertId();
    }

    public function update(array $data)
    {
        return $this->insert($data);
    }

    public function insert(array $data)
    {
        $this->data = $data;

        if(isset($this->data['id']) && !empty($this->data['id']) || !empty($this->where['field'])) {
            $this->buildUpdateQuery();
        } else {
            $this->buildSaveQuery();
        }

        if($this->execute())
            return true;

        return false;
    }

    public function delete()
    {
        $this->query = "DELETE FROM `{$this->table}` {$this->buildDeleteQuery()}";

        if($this->execute())
            return true;

        return false;
    }

    public function findOrFail()
    {
        $result = $this->get();

        if(empty($result))
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

    private function develop()
    {
        print_r([
            'Query' => $this->query,
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
