<?php

namespace PHPNomad\Database\Factories\Columns;

use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Interfaces\CanConvertToColumn;

class ForeignKeyFactory implements CanConvertToColumn
{
    protected string $name;
    protected string $referenceTableName;
    protected string $referenceColumn;

    public function __construct(string $name, string $referenceTableName, string $referenceColumn)
    {
        $this->name = $name;
        $this->referenceTableName = $referenceTableName;
        $this->referenceColumn = $referenceColumn;
    }

    public function toColumn(): Column
    {
        return new Column($this->name, 'FOREIGN KEY', null, $this->buildAttributes());
    }

    protected function buildAttributes(): string
    {
        return "REFERENCES $this->referenceTableName($this->referenceColumn)";
    }
}