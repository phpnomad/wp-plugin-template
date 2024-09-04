<?php

namespace PHPNomad\Database\Interfaces;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;

interface QueryStrategy
{
    /**
     * Executes a database query.
     *
     * @param QueryBuilder $builder. A fully-escaped, safe query string.
     * @return array
     * @throws DatastoreErrorException
     */
    public function query(QueryBuilder $builder): array;

    /**
     * Insert a record into the database.
     *
     * @param Table $table
     * @param array $data
     * @return array<string, int> The list of IDs necessary to identify this record.
     * @throws DatastoreErrorException
     */
    public function insert(Table $table, array $data): array;

    /**
     * Delete a record from the database.
     *
     * @param Table $table
     * @param array<string, int> $ids List of IDs necessary to identify this record.
     * @return void
     * @throws DatastoreErrorException
     */
    public function delete(Table $table, array $ids): void;

    /**
     * Update an existing record in the database.
     *
     * @param Table $table
     * @param array<string, int> $ids List of IDs necessary to identify this record.
     * @param array $data Data to update for this record.
     * @return void
     * @throws RecordNotFoundException
     * @throws DatastoreErrorException
     */
    public function update(Table $table, array $ids, array $data): void;

    /**
     * Retrieves an estimated count of records.
     *
     * @return int The estimated count of records.
     * @throws DatastoreErrorException
     */
    public function estimatedCount(Table $table): int;
}