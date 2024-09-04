<?php

namespace PHPNomad\Datastore\Interfaces;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;

interface JunctionHandler
{
    /**
     * Fetch a list of ids from the central table for the specified table.
     *
     * @param class-string<DataModel> $resource The name of the resource from which you want to receive the IDs.
     * @param int $id The ID associated with the opposite table as the table specified in the previous argument.
     * @param int $limit The limit of records to get.
     * @param int $offset The record offset.
     * @return int[] IDs associated with the table specified in $tableName.
     * @throws DatastoreErrorException
     */
    public function getIdsFromResource(string $resource, int $id, int $limit, int $offset): array;

    /**
     * Fetch a list of DataModels from the central table for the specified table.
     *
     * @param string $resource
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws DatastoreErrorException
     */
    public function getModelsFromResource(string $resource, int $id, int $limit, int $offset): array;

    /**
     * Fetch the number of records matching the specified ID
     *
     * @param string $resource
     * @param int $id
     * @return int
     * @throws DatastoreErrorException
     */
    public function getCountFromResource(string $resource, int $id): int;

    /**
     * Associates the specified ID with the junctioning ID of the other table.
     *
     * @param class-string<DataModel> $resource The name table the ID came from.
     * @param int $id The ID associated with the $resource argument.
     * @param int $bindingId The ID to bind the ID to.
     * @return void
     * @throws DuplicateEntryException
     * @throws DatastoreErrorException
     */
    public function bind(string $resource, int $id, int $bindingId): void;

    /**
     * Disassociates the specified ID with the junctioning ID of the other table.
     *
     * @param class-string<DataModel> $resource The name table the ID came from.
     * @param int $id The ID associated with the $resource argument.
     * @param int $bindingId The ID to bind the ID to.
     * @return void
     * @throws DatastoreErrorException
     */
    public function unbind(string $resource, int $id, int $bindingId): void;
}