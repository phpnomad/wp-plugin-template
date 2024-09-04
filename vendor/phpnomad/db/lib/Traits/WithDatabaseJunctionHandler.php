<?php

namespace PHPNomad\Database\Traits;

namespace PHPNomad\Database\Traits;

use InvalidArgumentException;
use PHPNomad\Database\Exceptions\RecordNotFoundException;
use PHPNomad\Datastore\Exceptions\DuplicateEntryException;
use PHPNomad\Datastore\Interfaces\JunctionContextProvider;
use PHPNomad\Utils\Helpers\Arr;

trait WithDatabaseJunctionHandler
{

    /**
     * Provider for the "left" datastore. Generally accessed using getContextForResource() or getOppositeContext()
     * to identify the correct one.
     *
     * @var JunctionContextProvider
     */
    public JunctionContextProvider $leftProvider;

    /**
     * Provider for the "right" datastore. Generally accessed using getContextForResource() or getOppositeContext()
     * to identify the correct one.
     *
     * @var JunctionContextProvider
     */
    public JunctionContextProvider $rightProvider;

    /**
     * Provider for the datastore that binds the two other datastores together.
     *
     * @var JunctionContextProvider
     */
    public JunctionContextProvider $middleProvider;

    /**
     * @param string $resource
     * @return JunctionContextProvider
     */
    protected function getContextforResource(string $resource): JunctionContextProvider
    {
        if ($this->leftProvider->getResource() === $resource) {
            return $this->leftProvider;
        }

        if ($this->rightProvider->getResource() === $resource) {
            return $this->rightProvider;
        }

        throw new InvalidArgumentException('The provided resource is invalid for this junction. Valid options are ' . $this->leftProvider->getResource() . ', ' . $this->rightProvider->getResource());
    }

    protected function getOppositeContext(JunctionContextProvider $junctionContextProvider): JunctionContextProvider
    {
        return $junctionContextProvider === $this->leftProvider ? $this->rightProvider : $this->leftProvider;
    }

    /** @inheritDoc */
    public function getIdsFromResource(string $resource, int $id, int $limit, int $offset): array
    {
        $context = $this->getContextForResource($resource);
        $opposite = $this->getOppositeContext($context);

        return $this->middleProvider->getDatastore()->andWhere([['column' => $opposite->getJunctionFieldName(), 'operator' => '=', 'value' => $id]], $limit, $offset);
    }

    /** @inheritDoc */
    public function bind(string $resource, int $id, int $bindingId): void
    {
        $context = $this->getContextForResource($resource);
        $binding = $this->getOppositeContext($context);

            $found = $this->middleProvider->getDatastore()->andWhere([
                ['column' => $binding->getJunctionFieldName(), 'operator' => '=', 'value' => $bindingId],
                ['column' => $context->getJunctionFieldName(), 'operator' => '=', 'value' => $id]
            ], 1);

            if(empty($found)) {
                $this->middleProvider->getDatastore()->create([
                    $binding->getJunctionFieldName() => $bindingId,
                    $context->getJunctionFieldName() => $id
                ]);
            }else{
                throw new DuplicateEntryException('The specified binding already exists');
            }
    }

    /** @inheritDoc */
    public function unbind(string $resource, int $id, int $bindingId): void
    {
        $context = $this->getContextForResource($resource);
        $binding = $this->getOppositeContext($context);

        $this->middleProvider->getDatastore()->deleteWhere([
            ['column' => $binding->getJunctionFieldName(), 'operator' => '=', 'value' => $bindingId],
            ['column' => $context->getJunctionFieldName(), 'operator' => '=', 'value' => $id]
        ]);
    }

    /** @inheritDoc */
    public function getModelsFromResource(string $resource, int $id, int $limit, int $offset): array
    {
        $context = $this->getContextForResource($resource);
        $ids = Arr::pluck($this->getIdsFromResource($resource, $id, $limit, $offset), $context->getJunctionFieldName());

        if (empty($ids)) {
            return [];
        }

        return $context->getDatastore()->findMultiple($ids);
    }

    /**
     * @inheritDoc
     */
    public function getCountFromResource(string $resource, int $id): int
    {
        $context = $this->getContextForResource($resource);

        return $this->middleProvider->getDatastore()->countAndWhere([
            ['column' => $context->getJunctionFieldName(), 'operator' => '=', 'value' => $id]
        ]);
    }
}