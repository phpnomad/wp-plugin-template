<?php

namespace PHPNomad\Datastore\Traits;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Datastore\Interfaces\Datastore;
use PHPNomad\Datastore\Interfaces\DatastoreHasPrimaryKey;

/**
 * @template TModel of DataModel
 * @method static instance()
 * @method DatastoreHasPrimaryKey getContainedInstance()
 */
trait WithDatastorePrimaryKeyFacadeMethods
{
    private static function getDatastoreHasPrimaryKeyInstance(): DatastoreHasPrimaryKey
    {
        return static::instance()->getContainedInstance();
    }

    /**
     * Retrieve a record by its primary key.
     *
     * @param mixed $id
     * @return DataModel
     * @throws DatastoreErrorException
     * @throws RecordNotFoundException
     */
    public static function find(int $id): DataModel
    {
        return static::getDatastoreHasPrimaryKeyInstance()->find($id);
    }

    /**
     * Update a record in the database.
     *
     * @param mixed $id
     * @param array<string, mixed> $attributes
     * @return void
     * @throws RecordNotFoundException
     * @throws DuplicateEntryException
     * @throws DatastoreErrorException
     */
    public static function update($id, array $attributes): void
    {
        static::getDatastoreHasPrimaryKeyInstance()->update($id, $attributes);
    }

    /**
     * Delete a record from the database.
     *
     * @param mixed $id
     * @return void
     * @throws DatastoreErrorException
     */
    public static function delete($id): void
    {
        static::getDatastoreHasPrimaryKeyInstance()->delete($id);
    }
}