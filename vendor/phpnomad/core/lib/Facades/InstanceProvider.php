<?php

namespace PHPNomad\Core\Facades;

use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Di\Interfaces\InstanceProvider as InstanceProviderAbstract;
use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Singleton\Traits\WithInstance;

/**
 * @extends Facade<InstanceProviderAbstract>
 */
class InstanceProvider extends Facade
{
    use WithInstance;

    /**
     * Get an instance of the class, with dependencies autowired
     *
     * @template T of object
     * @param class-string<T> $abstract
     * @return T
     * @throws DiException
     */
    public static function get(string $abstract): object
    {
        return static::instance()->getContainedInstance()->get($abstract);
    }

    protected function abstractInstance(): string
    {
        return InstanceProviderAbstract::class;
    }
}