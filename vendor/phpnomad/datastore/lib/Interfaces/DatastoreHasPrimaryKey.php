<?php

namespace PHPNomad\Datastore\Interfaces;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;

interface DatastoreHasPrimaryKey
{
    /**
     * Retrieve a record by its primary key.
     *
     * @param mixed $id
     * @return DataModel
     * @throws DatastoreErrorException
     * @throws RecordNotFoundException
     */
    public function find($id): DataModel;

    /**
     * Retrieve a set of records by their primary keys.
     *
     * @param array $ids
     * @return array
     * @throws DatastoreErrorException
     */
    public function findMultiple(array $ids): array;

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
    public function update($id, array $attributes): void;

    /**
     * Delete a record from the database.
     *
     * @param mixed $id
     * @return void
     * @throws DatastoreErrorException
     */
    public function delete($id): void;

    /**
     * Query the database with conditions.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return int[]
     * @throws DatastoreErrorException
     */
    public function findIds(array $conditions, ?int $limit = null, ?int $offset = null): array;
}