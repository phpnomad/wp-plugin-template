<?php

namespace PHPNomad\Framework\Traits;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Framework\Interfaces\CanResolveFields;
use PHPNomad\Registry\Traits\WithGet;

trait WithFieldResolver
{
    use WithGet;

    /**
     * @see CanResolveFields::resolve()
     */
    public function resolve(string $field, DataModel $model)
    {
        $item = $this->get($field);

        return $item($model);
    }
}