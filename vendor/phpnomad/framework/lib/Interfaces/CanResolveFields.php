<?php

namespace PHPNomad\Framework\Interfaces;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Registry\Interfaces\CanGet;

interface CanResolveFields extends CanGet
{
    /**
     * @param string $field The field to resolve
     * @param DataModel $model The collaborator to resolve against
     * @return mixed
     */
    public function resolve(string $field, DataModel $model);
}