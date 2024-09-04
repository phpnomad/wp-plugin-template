<?php

namespace PHPNomad\Database\Factories\Columns;

use PHPNomad\Database\Factories\Column;
use PHPNomad\Database\Interfaces\CanConvertToColumn;

class DateModifiedFactory implements CanConvertToColumn
{
    public function toColumn(): Column
    {
        return new Column('dateModified','TIMESTAMP', null, 'NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }
}