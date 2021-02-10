<?php

namespace App\Db\Eloquent;

trait Builder
{
    use Variables;
    
    public function prepareValuesForSelect($values): string
    {
        $select = '';
        foreach ($values as $key => $item) {
            if (is_object($item) === true) {
                $select .= $item->getValue().', ';
            } else {
                $select .= $this->prepareValueForWhere($item).', ';
            }
        }
    
        return rtrim($select, ', ');
    }
    
    public function prepareValueForWhere($value): string
    {
        $ret = '';
    
        if ((bool) strpos($value, '.') === true && (bool) preg_match('/( as )/', $value)) {
            $value = explode('.', $value);
            $ret .= " `{$this->trim($value[0])}`";
            $value = explode(' as ', $value[1]);
            $ret .= ".`{$this->trim($value[0])}` as `{$this->trim($value[1])}`";
        } else if ((bool) preg_match('/( as )/', $value) && (bool) strpos($value, '.') === false) {
            $value = explode(' as ', $value);
            $ret .= "`{$this->trim($value[0])}` as `{$this->trim($value[1])}`";
        } else if (! (bool) preg_match('/( as )/') && (bool) strpos($value, '.') === true) {
            $value = explode('.', $value);
            $ret .= "`{$this->trim($value[0])}`.`{$this->trim($value[1])}`";
        } else {
            $ret .= "`{$this->trim($value)}`";
        }

        return $ret;
    }
    
    public function trim($item): string
    {
        return trim($item);
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
    
    protected function setValue(string $key, string $value): string
    {
        do {
            $key = str_replace('.', '__', $key).'__'.rand(100,10000);
        } while (isset($this->data[$key]));
    
        $this->data[$key] = $value;
        return $key;
    }
    
    protected function appendToQuery(bool $isOr = false): void
    {
        if (! $this->isFirstWhere) {
            $this->isFirstWhere = true;
            $this->query .= " WHERE ";
        } else if ($isOr) {
            $this->query .= " OR ";
        } else {
            $this->query .= " AND ";
        }
        
        if ($this->startBracket) {
            $this->query .= ' ( ';
            $this->startBracket = false;
        }
    }
}
