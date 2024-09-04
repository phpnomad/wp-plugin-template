<?php

namespace PHPNomad\Database\Traits;

namespace PHPNomad\Database\Traits;

use PHPNomad\Database\Interfaces\Table;

trait WithUseTable
{
    /**
     * @var Table
     */
    protected Table $table;

    /** $inheritDoc */
    public function useTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

}