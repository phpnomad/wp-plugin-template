<?php

namespace PHPNomad\Datastore\Interfaces;

/**
 * @template TModel of DataModel
 */
interface ModelAdapter extends CanConvertModelToArray
{
    /**
     * @param array $array
     * @return TModel
     */
    public function toModel(array $array): DataModel;
}