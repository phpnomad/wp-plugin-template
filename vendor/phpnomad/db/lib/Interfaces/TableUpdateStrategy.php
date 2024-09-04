<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Exceptions\TableUpdateFailedException;

interface TableUpdateStrategy
{
    /**
     * Sync a table's columns based on the provided table definition.
     *
     * @param Table $table The table definition.
     *
     * @return void
     * @throws TableUpdateFailedException
     */
    public function syncColumns(Table $table): void;
}