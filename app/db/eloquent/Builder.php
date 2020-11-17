<?php
namespace App\Db\Eloquent;

abstract class Builder extends Field
{
    use Variables;
    
    public function __construct($model)
    {
        $this->table = $model::$table;
        if(isset($model::$id) === true) {
            $this->hasId = true;
        }
    }
    
    protected function buildOrder()
    {
        $tmp = '';
        
        foreach ($this->order['by'] as $order) {
            $tmp .= $this->checkHowToConnectValue($order, true) . ', ';
        }
        
        $tmp = rtrim($tmp, ', ');
        return " ORDER BY {$tmp} {$this->order['type']}";
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
            foreach ((array) $this->$table['field'] as $key => $value) {
                $join .= " {$this->$table['connector'][$iterator]} {$this->checkHowToConnectValue($this->$table['table'][$iterator], true, true)} ON
                           {$this->checkHowToConnectValue($this->$table['field'][$iterator], true)}
                           {$this->$table['comparison'][$iterator]} {$this->checkHowToConnectValue($this->$table['value'][$iterator], true)} ";
                ++$iterator;
            }

            return rtrim($join);
        }
        return '';
    }

    protected function push($arrayName, $field, $comparison, $value, $connector, $table = null)
    {
        if ($table !== null) {
            array_push($this->$arrayName['table'], trim($table));
        }

        array_push($this->$arrayName['field'], trim($field));
        array_push($this->$arrayName['comparison'], trim($comparison));
        array_push($this->$arrayName['value'], !is_array($value) ? trim($value) : $value);
        array_push($this->$arrayName['connector'], $connector);
    }
    
    protected function buildSaveQuery()
    {
        if(isset($this->data['id'])) {
            unset($this->data['id']);
        }
        
        $this->query = "INSERT INTO `{$this->table}` (";
        
        foreach ($this->data as $key => $field) {
            if ((string) $field !== '') {
                $this->query .= "`{$key}`, ";
            }
        }
        
        $this->query = rtrim($this->query, ', ') .") VALUES (";
        
        foreach ($this->data as $key => $field) {
            if ((string) $field !== '') {
                $this->query .= ":$key, ";
            }
        }
        
        $this->query = rtrim($this->query, ', ') .")";
    
        if ($this->onDuplicate === true) {
            $this->query .= ' ON DUPLICATE KEY UPDATE ';
            foreach ($this->data as $key => $field) {
                if ((string) $field !== '' || (string) $key !== 'id') {
                    $this->query .= "`{$key}` = :{$key}, ";
                }
            }
        }
    
        $this->query = rtrim($this->query, ', ');
        
        return true;
    }
    
    protected function buildUpdateQuery()
    {
        $this->query = "UPDATE `{$this->table}` SET ";
        
        if(array_key_exists('id', $this->data))
            $this->push('where', 'id', '=', ':id', 'AND');
    
        foreach ($this->data as $key => $value) {
            if ((string) $key === 'id') {
                continue;
            }
            if ((string) $value !== '') {
                $this->query .= "`{$key}` = :{$key}, ";
            }
        }
        
        $this->query = rtrim($this->query, ', ');
        
        $this->query .= $this->buildWhereQuery();
        
        return true;
    }
    
    protected function buildWhereQuery()
    {
        if (isset($this->where['field'][0])) {
            $where = " WHERE ";
            $iterator = 0;
    
            foreach ($this->where['field'] as $key => $value) {
                $connector = $this->where['connector'][$iterator + 1] ?? null;
                $where .= $this->where['field'][$iterator] . ' ' . $this->where['comparison'][$iterator];
                
                if($this->isSpecialVariable($this->where['value'][$iterator])) {
                    $where .= " {$this->where['value'][$iterator]} {$connector} ";
                } else {
                    if ((string) $key === 'id' || (string) $value === 'id') {
                        $this->where['field'][$iterator] = str_replace('.', '__', $this->where['field'][$iterator]);
                        $where .= " {$this->where['value'][$iterator]} {$connector} ";
                    } else {
                        $this->where['field'][$iterator] = str_replace('.', '__', $this->where['field'][$iterator]);
                        $where .= " :{$this->where['field'][$iterator]} {$connector} ";
                        $this->data[$this->where['field'][$iterator]] = $this->where['value'][$iterator];
                    }
                }
                
                ++$iterator;
            }
            
            return rtrim($where, ' ');
        }
        return '';
    }
    
    protected function buildWhereInQuery()
    {
        $in = implode("', '", $this->whereIn['value'])."'";
        $value = str_replace(',', '', $this->checkHowToConnectValue($this->whereIn['field']));
        if (isset($this->where['field'][0])) {
            $whereIn = " AND {$value} IN ('{$in})";
        } else {
            $whereIn = " WHERE {$value} IN ('{$in})";
        }
        
        return $whereIn;
    }
    
    protected function setData()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $key => $value) {
                if (is_null($value) === true || (string) $value === '') {
                    $this->data[$key] = null;
                } else {
                    $this->data[$key] = trim($value);
                }
            }
        }
    }
    
    private function isSpecialVariable($value)
    {
        return in_array($value, $this->specialVariables);
    }
}
