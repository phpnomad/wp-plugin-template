<?php

namespace PHPNomad\Database\Traits;

use PHPNomad\Datastore\Interfaces\Datastore;

trait WithDatabaseJunctionContextProvider
{

    /**
     * @return Datastore
     */
    public function getDatastore(): Datastore
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getJunctionFieldName(): string
    {
        return $this->junctionTableNamingService->getJunctionColumnNameFromTable($this->table);
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->table->getSingularUnprefixedName();
    }
}