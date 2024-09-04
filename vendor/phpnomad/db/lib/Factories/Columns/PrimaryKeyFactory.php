<?php

namespace PHPNomad\Database\Factories\Columns;

use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Interfaces\CanConvertToColumn;

class PrimaryKeyFactory implements CanConvertToColumn
{
    public function toColumn(): Column
    {
        return new Column('id','BIGINT',null,'AUTO_INCREMENT','PRIMARY KEY');
    }
}