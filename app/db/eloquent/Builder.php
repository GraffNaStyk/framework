<?php namespace App\Db\Eloquent;

abstract class Builder extends Field
{
    protected $where = ['field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    protected $values = '*';
    public $table;
    protected $query;
    protected $order = ['by' => '', 'type' => 'ASC'];
    protected $limit = '';
    protected $group = '';
    protected $distinct = false;
    protected $innerJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    protected $leftJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    protected $rightJoin = ['table' => [],'field' => [], 'comparison' => [], 'value' => [], 'connector' => []];
    protected $data;

    public function __construct($model)
    {
        $this->table = $model::$table;
    }

    protected function buildWhere()
    {
        if (isset($this->where['field'][0])) {
            $where = " WHERE (";
            $iterator = 0;

            foreach ($this->where['field'] as $key => $value) {
                $connector = $this->where['connector'][$iterator + 1] ?? null;
                $where .= "{$this->checkHowToConnectValue($this->where['field'][$iterator], true)} {$this->where['comparison'][$iterator]}";
                $where .= " {$this->detectFieldType($this->where['value'][$iterator], $this->where['comparison'][$iterator])} {$connector} ";
                ++$iterator;
            }

            return rtrim($where, ' ') . ')';
        }
        return '';
    }

    protected function buildOrder()
    {
        return " ORDER BY {$this->checkHowToConnectValue($this->order['by'], true)} {$this->order['type']}";
    }

    protected function buildGroup()
    {
        return " GROUP BY {$this->checkHowToConnectValue($this->group, true)}";
    }

    protected function buildDistinct()
    {
        return "DISTINCT ";
    }

    protected function buildJoin($table)
    {
        $iterator = 0;
        $join = '';
        if (!empty($this->$table)) {
            foreach ($this->$table['field'] as $key => $value) {
                $join .= " {$this->$table['connector'][$iterator]} `{$this->$table['table'][$iterator]}` ON {$this->checkHowToConnectValue($this->$table['field'][$iterator], true)} {$this->$table['comparison'][$iterator]} {$this->checkHowToConnectValue($this->$table['value'][$iterator], true)} ";
                ++$iterator;
            }

            return rtrim($join);
        }
        return '';
    }

    protected function push($arrayName, $field, $comparison, $value, $connector, $table = null)
    {
        if($table)
            array_push($this->$arrayName['table'], trim($table));

        array_push($this->$arrayName['field'], trim($field));
        array_push($this->$arrayName['comparison'], trim($comparison));
        array_push($this->$arrayName['value'], !is_array($value) ? trim($value) : $value);
        array_push($this->$arrayName['connector'], $connector);
    }

    protected function buildSaveQuery()
    {
        if(isset($this->data['id']))
            unset($this->data['id']);

        $this->query = "INSERT INTO `{$this->table}` (";

        foreach ($this->data as $key => $field)
            $this->query .= "{$key}, ";

        $this->query = rtrim($this->query, ', ') .") VALUES (";

        foreach ($this->data as $key => $field)
            $this->query .= ":$key, ";

        $this->query = rtrim($this->query, ', ') .")";

        return true;
    }

    protected function buildUpdateQuery()
    {
        $this->query = "UPDATE `{$this->table}` SET ";

        if(array_key_exists('id', $this->data))
            $this->push('where', 'id', '=', ':id', 'AND');

        foreach ($this->data as $key => $value) {
            if($key == 'id') continue;
            $this->query .= "{$key} = :{$key}, ";
        }

        $this->query = rtrim($this->query, ', ');

        $this->query .= $this->buildWhereForUpdate();

        return true;
    }

    protected function buildWhereForUpdate()
    {
        if (isset($this->where['field'][0])) {
            $where = " WHERE ";
            $iterator = 0;

            foreach ($this->where['field'] as $key => $value) {
                $connector = $this->where['connector'][$iterator + 1] ?? null;
                $where .= "{$this->where['field'][$iterator]} {$this->where['comparison'][$iterator]}";
                if(isset($this->data['id']))
                    $where .= " {$this->where['value'][$iterator]} {$connector} ";
                else {
                    $where .= " :{$this->where['field'][$iterator]} {$connector} ";
                    $this->data[$this->where['field'][$iterator]] = $this->where['value'][$iterator];
                }

                ++$iterator;
            }

            return rtrim($where, ' ');
        }
        return '';
    }

    protected function buildDeleteQuery()
    {
        if (isset($this->where['field'][0])) {
            $where = " WHERE ";
            $iterator = 0;

            foreach ($this->where['field'] as $key => $value) {
                $connector = $this->where['connector'][$iterator + 1] ?? null;
                $where .= "{$this->where['field'][$iterator]} {$this->where['comparison'][$iterator]}";

                $where .= " :{$this->where['field'][$iterator]} {$connector} ";
                $this->data[$this->where['field'][$iterator]] = $this->where['value'][$iterator];
                ++$iterator;
            }

            return rtrim($where, ' ');
        }

        return '';
    }
}
