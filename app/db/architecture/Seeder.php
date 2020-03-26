<?php namespace App\Db\Architecture;

class Seeder
{
    private $seedPath;
    public function __construct($fn)
    {
        require_once __DIR__ . '/Blueprint.php';
        $this->seedPath = __DIR__ . '/../Structure/*.php';
        $seeders = glob($this->seedPath, GLOB_BRACE);
        foreach ($seeders as $seed) {
            require_once $seed;
            $seedName = '\\App\\Db\\Structure\\' . basename(str_replace('.php','', $seed));
            $seedName = new $seedName();
            $seedName->$fn(new Blueprint($seedName->table, $seedName->alias));
        }
    }
}
