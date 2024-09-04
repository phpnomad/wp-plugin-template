<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Exceptions\TableCreateFailedException;
use PHPNomad\Database\Exceptions\TableDropFailedException;
use PHPNomad\Database\Exceptions\TableNotFoundException;

interface TableExistsStrategy
{
    /**
     * Check if a table exists in the database.
     *
     * @param string $tableName Name of the table to check.
     *
     * @return bool True if the table exists, false otherwise.
     */
    public function exists(string $tableName): bool;
}
