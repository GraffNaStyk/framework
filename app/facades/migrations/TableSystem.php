<?php
namespace App\Facades\Migrations;

trait TableSystem
{
    public function query($query)
    {
        $this->queries[] = $query;
    }
    
    public function hasColumn(string $table, string $name)
    {
        $result = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'" AND COLUMN_NAME = "'.$name.'" AND TABLE_SCHEMA = "'.$this->db->getDbName().'"');
        if (empty($result) === false) {
            return true;
        }
        
        return false;
    }
    
    public function hasTable(string $table)
    {
        $result = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'"  AND TABLE_SCHEMA = "'.$this->db->getDbName().'"');
        
        if (empty($result) === false) {
            return true;
        }
        
        return false;
        
    }
}
