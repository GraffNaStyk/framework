<?php namespace App\Db\Eloquent;
use stdClass;
use ReflectionClass;
abstract class Field
{
    public $hasId = false;

    protected function detectFieldType($field, $comparison)
    {
        if (!is_array($field) && strpos($field, 'CURDATE()') !== FALSE)
            return $field;

        if (is_array($field) && strtolower($comparison) == 'between')
            return implode(' AND ', $field);

        if (is_array($field) && strtolower($comparison) == 'in') {

            $returnFields = '';

            foreach ($field as $value) {
                if (!is_numeric($value))
                    $returnFields .= "'{$this->trim($value)}', ";
                else
                    $returnFields .= "$value, ";
            }

            $returnFields = rtrim($returnFields, ', ');

            return '(' . $returnFields . ')';
        }

        if ($field == '')
            return 'NULL';

        if (!is_numeric($field))
            return "'$field'";

        return $field;
    }

    protected function checkHowToConnectValue($val, $trim = false)
    {
        if(strpos($val, 'CASE'))
            return $val;

        //case when you write (table.field as tablefield)
        if (strpos($val, '.') && strpos($val = strtolower($val), ' as ')) {
            $val = explode('.', $val);
            $table = $val[0];
            $val = explode('as', $val[1]);
            $val[0] =  $this->checkIfValueIsStar($val[0]);
            $returnValues = "`{$this->trim($table)}`.{$this->trim($val[0])} as `{$this->trim($val[1])}`, ";
        }
        //case when you write (field as tablefield)
        else if (strpos($val = strtolower($val), ' as ') !== false) {
            $val = explode('as', $val);
            $val[0] = $this->checkIfValueIsStar($val[0]);
            $returnValues = "`{$this->trim($this->table)}`.{$this->trim($val[0])} as `{$this->trim($val[1])}`, ";
        }
        //case when you write (table.field)
        else if (strpos($val, '.') !== false) {
            $val = explode('.', $val);
            $val[1] =  $this->checkIfValueIsStar($val[1]);
            $returnValues = "`{$this->trim($val[0])}`.{$this->trim($val[1])}, ";
        }
        //case when you write (field)
        else {
            $returnValues = "`{$this->trim($this->table)}`.`{$this->trim($val)}`, ";
        }

        if($trim)
            return rtrim($returnValues, ', ');

        return $returnValues;
    }

    private function checkIfValueIsStar($val)
    {
        return $val == '*' ? $val : "`{$this->trim($val)}`";
    }

    protected function trim($value)
    {
        return trim($value);
    }

    protected function prepareValues()
    {
        if ($this->values == '*')
            return $this->values;

        $returnValues = '';
        if (is_array($this->values)) {

            if(!in_array('id', $this->values) && $this->hasId)
                array_push($this->values, 'id');

            foreach ($this->values as $val) {
                $returnValues .= $this->checkHowToConnectValue(trim($val));
            }
        }
        return rtrim($returnValues, ', ');
    }

    protected function formatJoin($data)
    {
        $result = [];
        if(isset($data['id'])) {
            foreach ($data as $key => $value) {
                if($isValue = $this->checkIfIdExistInObject($result, $value['id'])) {
                    $result[$isValue[1]]['join'] = array_diff(array_map('json_encode',(array)$value), array_map('json_encode',(array)$isValue[0]));
                } else $result[$key] = $value;
            }

            foreach ($result as $key => $value) {
                if(isset($value['join'])) {
                    foreach ($value['join'] as $joinKey => $joins)
                        $result[$key][$value]['join'][$joinKey] = json_decode($joins);
                }
            }
            return $result;
        }

        return $data;
    }

    protected function checkIfIdExistInObject($object, $id) {
        foreach ($object as $key => $value) {
            if($value['id'] == $id)
                return [$object[$key], $key];
        }
        return false;
    }
}
