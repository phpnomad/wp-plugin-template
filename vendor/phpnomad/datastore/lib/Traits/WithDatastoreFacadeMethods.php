<?php

namespace PHPNomad\Datastore\Traits;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Facade\Abstracts\Facade;

/**
 * @template TModel of DataModel
 * @method static Facade instance()
 */
trait WithDatastoreFacadeMethods
{
    private static function getFacadeInstance(): Datastore
    {
        return static::instance()->getContainedInstance();
    }

    /**
     * Query with conditions, using AND.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return DataModel[]
     * @throws DatastoreErrorException
     */
    public static function andWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
       return static::getFacadeInstance()->andWhere($conditions, $limit, $offset, $orderBy, $order);
    }

    /**
     * Query with conditions, using OR.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return DataModel[]
     * @throws DatastoreErrorException
     */
    public static function orWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
       return static::getFacadeInstance()->orWhere($conditions, $limit, $offset, $orderBy, $order);
    }

    /**
     * Insert a new record and return the instance.
     *
     * @param array<string, mixed> $attributes
     * @return DataModel The created model.
     * @throws DuplicateEntryException
     * @throws DatastoreErrorException
     */
    public static function create(array $attributes): DataModel
    {
       return static::getFacadeInstance()->create($attributes);
    }
}
