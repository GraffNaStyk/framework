<?php

namespace App\Facades\Migrations;

class Schema extends Blueprint
{
    public function __construct($model, $store = false)
    {
        parent::__construct($model, $store);
    }

    public function tinyint($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function smallint($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function mediumInt($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function int($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function varchar($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function text($name): Schema
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function mediumText($name): Schema
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function longText($name): Schema
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function timestamp($name): Schema
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function datetime($name): Schema
    {
        $this->generate($name, __FUNCTION__);
        return $this;
    }

    public function float($name, $length = null): Schema
    {
        $this->generate($name, __FUNCTION__, $length);
        return $this;
    }

    public function enum($name, $options): Schema
    {
        $this->generate($name, __FUNCTION__, "'".implode("', '", $options)."'");
        return $this;
    }

    public function null(): Schema
    {
        $this->tableFields[$this->currentKey] = str_replace(' NOT NULL', ' NULL DEFAULT NULL', $this->tableFields[$this->currentKey]);
        return $this;
    }

    public function implicitly(string $name): Schema
    {
        $default = $name === 'CURRENT_TIMESTAMP' ? $name : "'{$name}'";
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey].' DEFAULT '.$default;
        return $this;
    }

    public function default(string $name): Schema
    {
        $this->implicitly($name);
        return $this;
    }

    public function onUpdate(string $name): Schema
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey].' ON UPDATE '.$name;
        return $this;
    }

    public function unsigned(): Schema
    {
        $this->tableFields[$this->currentKey] = explode(')', $this->tableFields[$this->currentKey]);
        $this->tableFields[$this->currentKey] =
            $this->tableFields[$this->currentKey][0].') UNSIGNED '.$this->tableFields[$this->currentKey][1];
        return $this;
    }

    public function primary(): Schema
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey].' AUTO_INCREMENT';
        $this->otherImplementation .= ' PRIMARY KEY ('.$this->currentFieldName.'), ';
        return $this;
    }

    public function unique(array $others = []): Schema
    {
        $uniques = '';

        if (! empty($others)) {
            foreach ($others as $val)
                $uniques .= $val.', ';
        }

        if ((int) strlen($uniques) !== 0) {
            $uniques = rtrim($uniques, ', ');
            $uniques = ' , '.$uniques;
        }

        $this->otherImplementation .= ' UNIQUE ('.$this->currentFieldName.$uniques.'), ';
        return $this;
    }

    public function comment(string $comment): void
    {
        $this->tableFields[$this->currentKey] = $this->tableFields[$this->currentKey].' COMMENT '."'".$comment."'";
    }

    public function index(): Schema
    {
        $this->otherImplementation .= ' INDEX ('.$this->currentFieldName.'), ';
        return $this;
    }
	
	public function alter(string $field, $type, $length = null, $isNull = false, $default = null, $where = null): Schema
	{
		if ($isNull) {
			$qStr = ' DEFAULT NULL ';
		} else if ($isNull === false && $default !== null) {
			$qStr = ' NOT NULL DEFAULT '.($default === 'CURRENT_TIMESTAMP' ? $default : "'{$default}'");
		} else if ($isNull === true && $default !== null) {
			$qStr = ' DEFAULT '.($default === 'CURRENT_TIMESTAMP' ? $default : "'{$default}'");
		} else if ($isNull === false && $default === null) {
			$qStr = ' NOT NULL ';
		}
		
		$length = $length ? '('.$length.')' : $this->length[$type];
		$this->alter[] = 'ALTER TABLE `'.$this->table.'` ADD `'.$field.'` '.$type.' '.$length.' '.$qStr.' AFTER `'.$where.'`;';
		
		return $this;
	}

    public function foreign(array $reference = []): void
    {
        $this->foreign[] = 'ALTER TABLE '.$this->table.' ADD FOREIGN KEY ('.$this->currentFieldName.') REFERENCES '.
            $reference['table'].' (`'.$reference['field'].'`) ON DELETE '.strtoupper($reference['onDelete']).
            ' ON UPDATE '.strtoupper($reference['onUpdate']).';';
    }

    public function trigger($name, $when, $action, $body): void
    {
        $this->trigger[] = ' CREATE TRIGGER `'.$name.'` '.$when.' '.$action.' ON `'.
            $this->table.'` FOR EACH ROW BEGIN '.PHP_EOL.
            $body.';'.PHP_EOL.' END;';
    }
}
