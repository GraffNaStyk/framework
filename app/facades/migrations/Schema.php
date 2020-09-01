<?php
namespace App\Facades\Migrations;

class Schema extends Blueprint
{
    public function __construct($model, $store=false)
    {
        parent::__construct($model, $store);
    }

    public function tinyint($name, $length = null)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function smallint($name, $length = null)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function mediumInt($name, $length = null)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function int($name, $length = null)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function varchar($name , $length = null)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function tinyText($name)
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function text($name)
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function mediumText($name)
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function longText($name)
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function timestamp($name)
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function decimal($name, $length)
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function null()
    {
        $this->tableFields[$this->currentKey] = str_replace(' NOT NULL', ' NULL DEFAULT NULL', $this->tableFields[$this->currentKey]);
        return $this;
    }

    public function implicitly($name)
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey] . ' DEFAULT ' . $name;
        return $this;
    }

    public function onUpdate($name)
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey] . ' ON UPDATE ' . $name;
        return $this;
    }

    public function unsigned()
    {
        $this->tableFields[$this->currentKey] = explode(')', $this->tableFields[$this->currentKey]);
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey][0] . ') UNSIGNED ' . $this->tableFields[$this->currentKey][1];
        return $this;
    }

    public function primary()
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey] . ' AUTO_INCREMENT';
        $this->otherImplementation .= ' PRIMARY KEY ('.$this->currentFieldName.'), ';
        return $this;
    }

    public function unique($others = [])
    {
        $uniques = '';

        if(!empty($others)) {
            foreach ($others as $val)
                $uniques .= $val .', ';
        } else $uniques = '';


        if(strlen($uniques) != 0) {
            $uniques = rtrim($uniques, ', ');
            $uniques = ' , ' . $uniques;
        }

        $this->otherImplementation .= ' UNIQUE ('.$this->currentFieldName . $uniques  .'), ';
        return $this;
    }

    public function comment($comment)
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey] . ' COMMENT ' . "'" . $comment . "'";
    }

    public function index()
    {
        $this->otherImplementation .= ' INDEX ('.$this->currentFieldName.'), ';
        return $this;
    }

    public function alter($field, $type, $isNull=false, $default=null, $where=null)
    {
        $null = $isNull ? 'NOT NULL ' : 'DEFAULT NULL' . $default;
        $this->alter[] = 'ALTER TABLE `'. $this->table . '` ADD `'.$field.'` '.$type.' '.$this->length[$type].' '.$null.' '.$where.';';
        return $this;
    }

    public function foreign($reference = [])
    {
        $this->foreign .= 'ALTER TABLE '. $this->table . ' ADD FOREIGN KEY (`'.$this->currentFieldName.'`) REFERENCES '. $reference['table'] .
            ' (`'.$reference['field'].'`) ON DELETE ' . strtoupper($reference['onDelete']) . ' ON UPDATE ' . strtoupper($reference['onUpdate']) . ';';
    }

    public function trigger($name, $when, $action, $body)
    {
        $this->trigger[] = ' CREATE TRIGGER `' . $name . '` ' . $when . ' ' . $action . ' ON `' .
            $this->table .'` FOR EACH ROW BEGIN ' . PHP_EOL .
            $body . ';'. PHP_EOL . ' END;';
        
    }
}
