<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Exceptions\TableCreateFailedException;

interface TableCreateStrategy {
    /**
     * Create a table based on the provided table definition.
     *
     * @param Table $table The table definition.
     *
     * @return void
     * @throws TableCreateFailedException
     */
    public function create(Table $table): void;
}
