<?php

namespace PHPNomad\Datastore\Traits;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Datastore\Interfaces\DatastoreHasPrimaryKey;

trait WithDatastorePrimaryKeyDecorator
{
    /**
     * @var Datastore&DatastoreHasPrimaryKey
     */
    protected Datastore $datastoreHandler;

    public function find($id): DataModel
    {
        return $this->datastoreHandler->find($id);
    }

    public function findMultiple(array $ids): array
    {
        return $this->datastoreHandler->findMultiple($ids);
    }

    public function update($id, array $attributes): void
    {
        $this->datastoreHandler->update($id, $attributes);
    }

    public function delete($id): void
    {
        $this->datastoreHandler->delete($id);
    }

    public function findIds(array $conditions, ?int $limit = null, ?int $offset = null): array
    {
        return $this->datastoreHandler->findIds($conditions, $limit, $offset);
    }
}