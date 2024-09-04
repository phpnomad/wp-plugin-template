<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Database\Exceptions\TableDropFailedException;
use PHPNomad\Database\Interfaces\TableDeleteStrategy as CoreTableDeleteStrategy;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Integrations\WordPress\Traits\CanModifyWordPressDatabase;

class TableDeleteStrategy implements CoreTableDeleteStrategy
{
    use CanModifyWordPressDatabase;

    /**
     * Drops the specified table.
     *
     * @param string $tableName
     * @return void
     * @throws TableDropFailedException
     */
    public function delete(string $tableName): void
    {
        try {
            $this->wpdbQuery("DROP TABLE IF EXISTS $tableName");
        } catch (DatastoreErrorException $e) {
            throw new TableDropFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}