<?php

namespace PHPNomad\Framework\Traits;

use PHPNomad\Datastore\Interfaces\DataModel;
use PHPNomad\Framework\Interfaces\CanResolveFields;

trait CanGetResolvedFields
{
    protected CanResolveFields $fieldResolverRegistry;

    /**
     * @param array $fields
     * @param DataModel[] $records
     * @return array
     */
    protected function getResolvedFields(array $fields, array $records): array
    {
        $result = [];

        foreach ($records as $record) {
            $resolved = [];
            foreach ($fields as $field) {
                //TODO: THIS SHOULD THROW AN EXCEPTION, AND THAT EXCEPTION SHOULD BE CAPTURED HERE, AND COLLATED.
                // THAT WAY THE RESPONSE GETS ALL OF THE FAILED RESOLVERS AS ERRORS.
                $resolved[$field] = $this->fieldResolverRegistry->resolve($field, $record);
            }

            $result[] = $resolved;
        }

        return $result;
    }
}