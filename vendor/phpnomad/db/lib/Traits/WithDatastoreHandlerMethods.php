<?php

namespace PHPNomad\Database\Traits;

use PHPNomad\Cache\Enums\Operation;
use PHPNomad\Core\Facades\Event;
use PHPNomad\Database\Events\RecordCreated;
use PHPNomad\Database\Events\RecordDeleted;
use PHPNomad\Database\Events\RecordUpdated;
use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Database\Interfaces\Table;
use PHPNomad\Database\Providers\DatabaseServiceProvider;
use PHPNomad\Database\Services\TableSchemaService;
use PHPNomad\Datastore\Exceptions\DatastoreErrorException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;
use PHPNomad\Datastore\Interfaces\CanIdentify;
use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Datastore\Interfaces\HasSingleIntIdentity;
use PHPNomad\Datastore\Interfaces\ModelAdapter;
use PHPNomad\Utils\Helpers\Arr;
use PHPNomad\Utils\Helpers\Obj;

trait WithDatastoreHandlerMethods
{
    protected DatabaseServiceProvider $serviceProvider;
    protected Table $table;
    protected TableSchemaService $tableSchemaService;

    /**
     * @var class-string<DataModel>
     */
    protected string $model;
    protected ModelAdapter $modelAdapter;

    /**
     * @inheritDoc
     */
    public function getEstimatedCount(): int
    {
        return $this->serviceProvider->cacheableService
            ->getWithCache('estimatedCount', ['type' => $this->model], function () {
                return $this->serviceProvider->queryStrategy->estimatedCount($this->table);
            });
    }

    /** @inheritDoc */
    public function where(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        try {
            $this->initiateQuery($limit, $offset, $orderBy, $order)->buildConditions($conditions);
            $ids = $this->serviceProvider->queryStrategy->query($this->serviceProvider->queryBuilder);

            return $this->getModels($ids);
        }catch(RecordNotFoundException $e){
            return [];
        }
    }

    /** @inheritDoc */
    public function andWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        return $this->where([
            [
                'type' => 'AND',
                'clauses' => $conditions
            ],
        ], $limit, $offset, $orderBy, $order);
    }

    /** @inheritDoc */
    public function orWhere(array $conditions, ?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC'): array
    {
        return $this->where([
            [
                'type' => 'OR',
                'clauses' => $conditions
            ]
        ], $limit, $offset, $orderBy, $order);
    }

    /** @inheritDoc */
    public function countWhere(array $conditions): int
    {
        $this->initiateQuery(
            null,
            null,
            null,
            'ASC',
            [],
        )->buildConditions($conditions);

        $this->serviceProvider->queryBuilder->count('*', 'count');

        try {
            $results = $this->serviceProvider->queryStrategy->query($this->serviceProvider->queryBuilder);
        } catch (RecordNotFoundException $e) {
            return 0;
        }

        $result = (array)Arr::first($results);

        return Arr::get($result, 'count', 0);
    }

    /** @inheritDoc */
    public function countAndWhere(array $conditions): int
    {
        return $this->countWhere([
            [
                'type' => 'AND',
                'clauses' => $conditions
            ]
        ]);
    }

    /** @inheritDoc */
    public function countOrWhere(array $conditions): int
    {
        return $this->countWhere([
            [
                'type' => 'OR',
                'clauses' => $conditions
            ]
        ]);
    }

    /** @inheritDoc */
    public function findBy(string $field, $value): DataModel
    {
        $result = $this->andWhere([['column' => $field, 'operator' => '=', 'value' => $value]], 1);

        if(empty($result)){
            throw new RecordNotFoundException("Could not find a record where $field equals $value");
        }

        return Arr::get($result, 0);
    }

    /** @inheritDoc */
    public function create(array $attributes): DataModel
    {
        $fields = $this->table->getFieldsForIdentity();

        if (Obj::implements($this->model, HasSingleIntIdentity::class)) {
            $attributes = $this->removeIdentifiableFields($attributes, $fields);
        } else {
            $this->maybeThrowForDuplicateIdentity($attributes, $fields);
        }

        $this->maybeThrowForDuplicateUniqueFields($attributes);

        $ids = $this->serviceProvider->queryStrategy->insert($this->table, $attributes);

        $result = Arr::first($this->getModels([$ids]));

        if(!$result){
            throw new DatastoreErrorException('Failed to create the record');
        }

        Event::broadcast(new RecordCreated($result));

        return $result;
    }

    /**
     * Delete all items that fit the specified condition.
     *
     * @param array $conditions
     * @return void
     * @throws DatastoreErrorException
     */
    public function deleteWhere(array $conditions): void
    {
        $items = $this->andWhere($conditions);

        foreach ($items as $item) {
            $identity = $item->getIdentity();

            $this->serviceProvider->queryStrategy->delete($this->table, $identity);
            $this->serviceProvider->cacheableService->delete($this->getCacheContextForItem($identity));

            Event::broadcast(new RecordDeleted($this->model, $identity));
        }
    }

    /**
     * @return $this
     */
    protected function initiateQuery(?int $limit = null, ?int $offset = null, ?string $orderBy = null, string $order = 'ASC', array $select = null)
    {
        $this->serviceProvider->clauseBuilder->useTable($this->table);
        $select = $select === null ? $this->table->getFieldsForIdentity() : $select;


        $this->serviceProvider->queryBuilder
            ->reset()
            ->from($this->table);

        if(!empty($select)){
            $this->serviceProvider->queryBuilder->select(...$select);
        }

        if ($limit) {
            $this->serviceProvider->queryBuilder->limit($limit);
        }

        if ($offset) {
            $this->serviceProvider->queryBuilder->offset($offset);
        }

        if ($orderBy) {
            $this->serviceProvider->queryBuilder->orderBy($orderBy, $order);
        }

        return $this;
    }

    /**
     * Takes the given array of conditions and adds it to the query builder as a where statement.
     *
     * @param array $groups
     * @return $this
     */
    protected function buildConditions(array $groups)
    {
        foreach ($groups as $group) {
            $clauses = Arr::get($group, 'clauses', []);
            $groupClauseBuilder = (clone $this->serviceProvider->clauseBuilder)->reset()->useTable($this->table);
            $type = strtoupper(Arr::get($group, 'type'));
            if($clauses) {
                $firstClause = array_shift($clauses);
                $column = Arr::get($firstClause, 'column');
                $operator = Arr::get($firstClause, 'operator');
                $value = array_values(Arr::wrap(Arr::get($firstClause, 'value', [])));

                $groupClauseBuilder->where($column, $operator, ...$value);

                foreach ($clauses as $clause) {
                    $column = Arr::get($clause, 'column');
                    $operator = Arr::get($clause, 'operator');
                    $value = Arr::get($clause, 'value');

                    if ($type === 'OR') {
                        $groupClauseBuilder->orWhere($column, $operator, ...Arr::wrap($value));
                    } else {
                        $groupClauseBuilder->andWhere($column, $operator, ...Arr::wrap($value));
                    }
                }

                $type = strtoupper(Arr::get($group, 'type', 'AND'));
                $type = in_array($type, ['AND', 'OR']) ? $type : 'AND';

                $groupType = strtoupper(Arr::get($group, 'groupType', 'and'));

                if ($groupType === 'OR') {
                    $this->serviceProvider->clauseBuilder->orGroup($type, $groupClauseBuilder);
                } else {
                    $this->serviceProvider->clauseBuilder->andGroup($type, $groupClauseBuilder);
                }
            }
        }

        if(!empty($groups)) {
            $this->serviceProvider->queryBuilder->where($this->serviceProvider->clauseBuilder);
        }

        return $this;
    }

    /**
     * Gets the cache context for the given ID.
     *
     * @param array<string, int> $identities list of identities keyed by the field name for the identity.
     * @return array
     */
    protected function getCacheContextForItem(array $identities): array
    {
        return ['identities' => Arr::merge($identities), 'type' => $this->model];
    }

    /**
     * Caches items in-batch
     *
     * @param DataModel[] $models
     *
     * @return void
     */
    protected function cacheItems(array $models): void
    {
        Arr::map($models, fn(DataModel $model) => $this->serviceProvider->cacheableService->set(
            $this->getCacheContextForItem($model->getIdentity()),
            $model
        ));
    }

    /**
     * Converts the given dataset into model objects.
     *
     * @param array $data
     *
     * @return DataModel[]
     */
    protected function hydrateItems(array $data): array
    {
        return Arr::map($data, [$this->modelAdapter, 'toModel']);
    }

    /**
     * @param array $conditions
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws DatastoreErrorException
     */
    public function findIds(array $conditions, ?int $limit = null, ?int $offset = null): array
    {
        $this->serviceProvider->queryBuilder
            ->from($this->table)
            ->select(...$this->table->getFieldsForIdentity());


        if ($limit) {
            $this->serviceProvider->queryBuilder->limit($limit);
        }

        if ($offset) {
            $this->serviceProvider->queryBuilder->offset($offset);
        }

        $this->buildConditions($conditions);

        return $this->serviceProvider->queryStrategy->query($this->serviceProvider->queryBuilder);
    }

    /**
     * Gets the models from the specified list of IDs.
     *
     * @param array<string, int>[] $ids
     * @return array
     */
    protected function getModels(array $ids): array
    {
        // Filter out the items that are currently in the cache.
        $idsToQuery = Arr::filter(
            $ids,
            fn(array $ids) => !$this->serviceProvider->cacheableService->exists($this->getCacheContextForItem($ids))
        );

        if (!empty($idsToQuery)) {
            try {
                $clauseBuilder = (clone $this->serviceProvider->clauseBuilder)->reset()->useTable($this->table);
                // Get the things that aren't in the cache.
                $data = $this->serviceProvider->queryStrategy->query(
                    $this->serviceProvider->queryBuilder
                        ->from($this->table)
                        ->select('*')
                        ->where($clauseBuilder->andWhere($this->table->getFieldsForIdentity(), 'IN', ...$idsToQuery))
                );
            } catch (DatastoreErrorException $e) {
                $this->serviceProvider->loggerStrategy->logException($e, 'Could not get by IDs');
                return [];
            }

            // Cache those items.
            $this->cacheItems($this->hydrateItems($data));
        }

        // Now, use the cache to get all the posts in the proper order.
        return Arr::map($ids, fn(array $id) => $this->findFromCompound($id));
    }

    /**
     * @param non-empty-array<string, int> $ids
     * @return mixed
     * @throws DatastoreErrorException
     * @throws RecordNotFoundException
     */
    protected function findFromCompound(array $ids)
    {
        if (empty($ids)) {
            throw new RecordNotFoundException('Record cannot be found, no IDs provided.');
        }

        return $this->serviceProvider->cacheableService->getWithCache(
            Operation::Read,
            $this->getCacheContextForItem($ids),
            function () use ($ids) {
                $clauseBuilder = (clone $this->serviceProvider->clauseBuilder)->reset()->useTable($this->table);

                foreach($ids as $key => $id){
                    $clauseBuilder->andWhere($key, '=', $id);
                }

                $items = $this->serviceProvider->queryStrategy->query(
                    $this->serviceProvider->queryBuilder
                        ->select('*')
                        ->from($this->table)
                        ->where($clauseBuilder)
                        ->limit(1)
                );

                $item = Arr::get($items, 0);

                if (!$item) {
                    throw new RecordNotFoundException('Record not found using the provided identity');
                }

                return $this->modelAdapter->toModel($item);
            }
        );
    }

    /** @inheritDoc */
    public function updateCompound($ids, array $attributes): void
    {
        $this->findFromCompound($ids);
        $this->maybeThrowForDuplicateUniqueFields($attributes, $ids);

        $this->serviceProvider->queryStrategy->update($this->table, $ids, $attributes);
        $this->serviceProvider->cacheableService->delete($this->getCacheContextForItem($ids));

        Event::broadcast(new RecordUpdated($this->model, $ids, $attributes));
    }

    /**
     * @param array $attributes
     * @param array $fields
     * @return void
     * @throws DatastoreErrorException
     * @throws DuplicateEntryException
     */
    protected function maybeThrowForDuplicateIdentity(array $attributes, array $fields): void
    {
        $ids = [];

        foreach ($attributes as $fieldName => $value) {
            if (in_array($fieldName, $fields)) {
                $ids[$fieldName] = $value;
            }
        }

        // Validate item does not already exist.
        if (count($ids) === count($fields)) {
            try {
                $this->findFromCompound($ids);

                $identity = Arr::process($ids)
                    ->each(fn($item, $key) => $key . ' => ' . $item)
                    ->setSeparator(', ')
                    ->toString();

                throw new DuplicateEntryException('The specified item identified as ' . $identity . ' already exists.');
            } catch (RecordNotFoundException $e) {
                //continue
            }
        }
    }

    /**
     * @param array $attributes
     * @param array $fields
     * @return array
     */
    protected function removeIdentifiableFields(array $attributes, array $fields): array
    {
        return Arr::filter($attributes, fn($value, $fieldName) => !in_array($fieldName, $fields));
    }

    /**
     * Looks up records to check if a record with the specified unique columns already exists.
     *
     * @param array $data
     * @return DataModel[] List of existing items that match the unique constraints.
     * @throws DatastoreErrorException
     * @throws RecordNotFoundException
     */
    protected function getDuplicates(array $data): array
    {
        $uniqueColumnGroups = $this->tableSchemaService->getUniqueColumns($this->table);
        $groups = [];

        foreach ($uniqueColumnGroups as $uniqueColumns) {
            $clauses = [];
            foreach ($uniqueColumns as $columnName) {
                if (isset($data[$columnName])) {
                    $clauses[] = [
                        'column' => $columnName,
                        'operator' => '=',
                        'value' => $data[$columnName]
                    ];
                } else {
                    // If any column in a unique index (compound key) does not have data provided, skip this group
                    $clauses = [];
                    break;
                }
            }

            // Only add this group to the query if it has conditions for all columns in the unique index
            if (!empty($clauses)) {
                $groups[] = [
                    'type' => 'AND',
                    'groupType' => 'OR',
                    'clauses' => $clauses
                ];
            }
        }

        if(empty($groups)){
            return [];
        }

        return $this->where($groups); // Assuming where method is adapted to handle groups
    }


    /**
     * @param array $data
     * @param array|null $updateIdentity
     * @return void
     * @throws DuplicateEntryException
     * @throws DatastoreErrorException
     */
    protected function maybeThrowForDuplicateUniqueFields(array $data, ?array $updateIdentity = null): void
    {
        try {
            $duplicates = $this->getDuplicates($data);

            // If an identity is provided, filter out items that have the provided identity.
            if (!is_null($updateIdentity)) {
                $duplicates = Arr::filter(
                    $duplicates,
                    fn(CanIdentify $existingItem) => !Arr::containsSameData($existingItem->getIdentity(), $updateIdentity)
                );
            }
        } catch (RecordNotFoundException $e) {
            // Bail if no records were found.
            return;
        }

        if (!empty($duplicates)) {
            throw new DuplicateEntryException('Database operation stopped early because duplicate entries were detected.');
        }
    }
}