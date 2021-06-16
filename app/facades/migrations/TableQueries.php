<?php

namespace App\Facades\Migrations;

trait TableQueries
{
    public function query($query): void
    {
        $this->queries[] = $query;
    }

    public function hasColumn(string $table, string $name): bool
    {
        $result = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'"
                        AND COLUMN_NAME = "'.$name.'" AND TABLE_SCHEMA = "'.$this->db->getDbName().'"');

        if (! empty($result)) {
            return true;
        }

        return false;
    }

    public function hasTable(string $table): bool
    {
        $result = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'"
                        AND TABLE_SCHEMA = "'.$this->db->getDbName().'"');

        if (! empty($result)) {
            return true;
        }

        return false;
    }

    public function hasRecord(string $table, string $column, string $record): bool
    {
        $res = $this->db->query('SELECT `'.$column.'` FROM '.$table.' WHERE `'.$column.'` = "'.$record.'"');

        if (! empty($res)) {
            return true;
        }

        return false;
    }
}