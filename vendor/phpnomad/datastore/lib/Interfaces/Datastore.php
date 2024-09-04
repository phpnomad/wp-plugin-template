<?php

namespace PHPNomad\Datastore\Interfaces;

use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;

interface Datastore
{
    /**
     * Gets the estimated number of records in this datastore.
     * Note this is not intended to be 100% accurate, but should be close-enough for the majority of scenarios.
     *
     * @return int
     * @throws DatastoreErrorException
     */
    public function getEstimatedCount(): int;

    /**
     * Query with conditions, using a combination of AND/OR.
     * Classes implementing this should assume type and groupType are AND if they are not set.
     *
     * @param array{type?: string, groupType?: string, clauses: array{column: string, operator: string, value: mixed}[]}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return DataModel[]
     * @throws DatastoreErrorException
     */
    public function where(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array;

    /**
     * Query with conditions, using AND.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return DataModel[]
     * @throws DatastoreErrorException
     */
    public function andWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array;

    /**
     * Query with conditions, using OR.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     * @return DataModel[]
     * @throws DatastoreErrorException
     */
    public function orWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array;


    /**
     * Count the results with conditions, using AND/OR.
     *
     * @param array{type: string, clauses: array{column: string, operator: string, value: mixed}[]} $conditions
     * @return int
     * @throws DatastoreErrorException
     */
    public function countWhere(array $conditions): int;

    /**
     * Count the results with conditions, using AND.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @return int
     * @throws DatastoreErrorException
     */
    public function countAndWhere(array $conditions): int;

    /**
     * Count the results with conditions, using OR.
     *
     * @param array{column: string, operator: string, value: mixed}[] $conditions
     * @return int
     * @throws DatastoreErrorException
     */
    public function countOrWhere(array $conditions): int;

    /**
     * Finds the first available record that has the specified value in the specified column.
     *
     * @param string $field
     * @param $value
     * @return DataModel
     * @throws DatastoreErrorException
     * @throws RecordNotFoundException
     */
    public function findBy(string $field, $value): DataModel;

    /**
     * Insert a new record and return the instance.
     *
     * @param array<string, mixed> $attributes
     * @return DataModel The created model.
     * @throws DuplicateEntryException
     * @throws DatastoreErrorException
     */
    public function create(array $attributes): DataModel;

    /**
     * Delete a record from the database.
     *
     * @param array{string, string} $conditions - field values keyed by their respective field.
     * @return void
     * @throws DatastoreErrorException
     */
    public function deleteWhere(array $conditions): void;

    /**
     * Update a record in the database.
     *
     * @param array<string, int> $ids
     * @param array<string, mixed> $attributes
     * @return void
     * @throws RecordNotFoundException
     * @throws DatastoreErrorException
     */
    public function updateCompound(array $ids, array $attributes): void;
}
