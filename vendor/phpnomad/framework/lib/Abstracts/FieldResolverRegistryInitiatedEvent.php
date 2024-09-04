<?php

namespace PHPNomad\Framework\Abstracts;

use PHPNomad\Events\Interfaces\Event;
use PHPNomad\Registry\Interfaces\CanDelete;
use PHPNomad\Registry\Interfaces\CanGet;
use PHPNomad\Registry\Interfaces\CanSet;

abstract class FieldResolverRegistryInitiatedEvent implements Event
{
    /**
     * @var object&CanDelete&CanSet&CanGet
     */
    protected object $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function deleteResolver(string $id)
    {
        $this->registry->delete($id);
    }

    /**
     * @param string $field The field to listen for
     * @param callable $resolver The function used to resolve this field.
     * @return void
     */
    public function addResolver(string $field, callable $resolver): void
    {
        $this->registry->set($field, fn() => $resolver);
    }
}