<?php

namespace PHPNomad\Datastore\Traits;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Datastore\Interfaces\Datastore;

trait WithDatastoreDecorator
{
    protected Datastore $datastoreHandler;

    public function where(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        return $this->datastoreHandler->where($conditions, $limit, $offset, $orderBy, $order);
    }

    public function countWhere(array $conditions): int
    {
        return $this->datastoreHandler->countWhere($conditions);
    }

    /** @inheritDoc */
    public function andWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        return $this->datastoreHandler->andWhere($conditions, $limit, $offset, $orderBy, $order);
    }

    /** @inheritDoc */
    public function orWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        return $this->datastoreHandler->orWhere($conditions, $limit, $offset, $orderBy, $order);
    }

    /** @inheritDoc */
    public function countAndWhere(array $conditions): int
    {
        return $this->datastoreHandler->countAndWhere($conditions);
    }

    /** @inheritDoc */
    public function countOrWhere(array $conditions): int
    {
        return $this->datastoreHandler->countOrWhere($conditions);
    }

    /** @inheritDoc */
    public function findBy(string $field, $value): DataModel
    {
        return $this->datastoreHandler->findBy($field, $value);
    }

    /** @inheritDoc */
    public function create(array $attributes): DataModel
    {
        return $this->datastoreHandler->create($attributes);
    }

    /** @inheritDoc */
    public function deleteWhere(array $conditions): void
    {
        $this->datastoreHandler->deleteWhere($conditions);
    }

    /** @inheritDoc */
    public function updateCompound(array $ids, array $attributes): void
    {
        $this->datastoreHandler->updateCompound($ids, $attributes);
    }

    /** @inheritDoc */
    public function getEstimatedCount(): int
    {
        return $this->datastoreHandler->getEstimatedCount();
    }
}