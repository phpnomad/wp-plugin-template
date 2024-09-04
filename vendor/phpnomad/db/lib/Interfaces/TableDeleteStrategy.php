<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Exceptions\TableCreateFailedException;
use PHPNomad\Database\Exceptions\TableDropFailedException;
use PHPNomad\Database\Exceptions\TableNotFoundException;

interface TableDeleteStrategy {
    /**
     * Drop or delete a table from the database.
     *
     * @param string $tableName Name of the table to drop.
     *
     * @return void
     * @throws TableDropFailedException
     */
    public function delete(string $tableName): void;
}
