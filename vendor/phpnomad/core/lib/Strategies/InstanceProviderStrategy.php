<?php

namespace PHPNomad\Core\Strategies;

use PHPNomad\Di\Container;
use PHPNomad\Di\Interfaces\InstanceProvider;

class InstanceProviderStrategy implements InstanceProvider
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $abstract): object
    {
        return $this->container->get($abstract);
    }
}