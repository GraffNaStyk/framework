<?php namespace App\Db\Architecture;

use App\Db\Model;

class Core
{
    protected $startSql = 'CREATE TABLE IF NOT EXISTS ';
    protected $endSql = ' )';
    protected $sql;
    protected $table;
    protected $alias;
    protected $notNull = ' NOT NULL';
    protected $tableFields = [];
    protected $length = [
        'tinyint' => '(2)',
        'smallint' => '(4)',
        'mediumint' => '(8)',
        'int' => '(10)',
        'char' => '(255)',
        'varchar' => '(255)',
        'boolean' => '(255)',
        'tinytext' => '(50)',
        'text' => '(255)',
        'mediumText' => '(255)',
        'longText' => '(255)',
        'timestamp' => ' '
    ];

    protected $currentKey = '';
    protected $currentFieldName = '';
    protected $lastCalled;
    protected $otherImplementation;
    protected $foreign = '';
    protected $model;
    protected $trigger = [];

    public function __construct($nameTable = null, $nameAlias = null)
    {
        $this->table = $nameTable;
        $this->alias = $nameAlias;

        $env = require str_replace('Db/Architecture', 'Config/.env', __DIR__);

        require_once __DIR__ . '/../Model.php';

        Model::$env['DB'] = $env['DB'];
        $this->model = new Model();
    }

    private function lastKey()
    {
        return array_keys($this->tableFields)[count($this->tableFields)-1];
    }

    protected function generate($name, $fnName, $length = null)
    {
        $this->lastCalled = $fnName;
        $this->currentFieldName = $name;
        $this->tableFields[] = $name . ' ' . $this->lastCalled .($length ? '(' . $length . ')' : $this->length[$this->lastCalled]). $this->notNull;
        $this->currentKey = $this->lastKey();
    }

    public function run()
    {
        $this->tableFields = implode(', ', $this->tableFields);
        $this->tableFields = rtrim($this->tableFields, ',');
        $this->otherImplementation = rtrim($this->otherImplementation, ', ');
        $this->sql = $this->startSql . '`' . trim($this->table) . '`' . ' ( ' . $this->tableFields . ', ' . $this->otherImplementation . $this->endSql;

        $this->model->query($this->sql);
        $this->foreign ?? $this->model->query($this->foreign);

        if(!empty($this->trigger))
            foreach ($this->trigger as $trigger)
                $this->model->query($trigger);

        if(!is_file(str_replace('Db/Architecture', 'Model', __DIR__).'/'.$this->alias.'.php')) {
            $content = file_get_contents(__DIR__.'/../Examples/ModelExample');
            $content = str_replace('CLASSNAME', $this->alias, $content);
            $content = str_replace('NAMETABLE', $this->table, $content);
            $content = str_replace('NAMEALIAS', $this->alias, $content);

            file_put_contents(str_replace('Db/Architecture', 'Model', __DIR__).'/'.$this->alias.'.php', '<?php ' . $content);
        }
    }

    public function clear()
    {
        $this->model->query('DROP TABLE ' . $this->table);
    }
}
