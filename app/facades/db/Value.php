<?php

namespace App\Facades\Db;

class Value
{
    private string $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}