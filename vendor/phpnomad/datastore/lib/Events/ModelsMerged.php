<?php

namespace PHPNomad\Datastore\Events;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Events\Interfaces\Event;

/**
 * @template T of DataModel
 */
class ModelsMerged implements Event
{
    protected DataModel $mergedModel;
    protected array $removedModels;
    protected DataModel $originalModel;

    /**
     * @param T $mergedModel
     * @param T $originalModel
     * @param T[] $removedModels
     */
    public function __construct(DataModel $mergedModel, DataModel $originalModel, array $removedModels)
    {
        $this->originalModel = $originalModel;
        $this->mergedModel = $mergedModel;
        $this->removedModels = $removedModels;
    }

    /**
     * @return T
     */
    public function getOriginalModel(): DataModel
    {
        return $this->originalModel;
    }

    /**
     * @return T[]
     */
    public function getRemovedModels(): array
    {
        return $this->removedModels;
    }

    /**
     * @return T
     */
    public function getMergedModel(): DataModel
    {
        return $this->mergedModel;
    }

    public static function getId(): string
    {
        return 'datastore_models_merged';
    }
}