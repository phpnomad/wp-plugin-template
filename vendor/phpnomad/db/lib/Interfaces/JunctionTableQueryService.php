<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;

interface JunctionTableQueryService
{
    /**
     * Fetch a list of records for the specified table.
     *
     * @param string $tableName The name of the table from which you want to receive the IDs.
     * @param int $id The ID associated with the opposite table as the table specified in the previous argument.
     * @param int $limit The limit of records to get.
     * @param int $offset The record offset.
     * @return int[] IDs associated with the table specified in $tableName.
     * @throws DatastoreErrorException
     */
    public function getIdsFromTable(string $tableName, int $id, int $limit, int $offset): array;

    /**
     * Associates the specified ID with the junctioning ID of the other table.
     *
     * @param string $tableName The name table the ID came from.
     * @param int $id The ID associated with the $table argument.
     * @param int $bindingId The ID to bind the ID to.
     * @return void
     *@throws DuplicateEntryException
     */
    public function bind(string $table, int $id, int $bindingId): void;

    /**
     * Disassociates the specified ID with the junctioning ID of the other table.
     *
     * @param string $tableName The name table the ID came from.
     * @param int $id The ID associated with the $table argument.
     * @param int $bindingId The ID to bind the ID to.
     * @return void
     */
    public function unbind(string $table, int $id, int $bindingId): void;
}