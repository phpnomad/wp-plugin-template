<?php

namespace PHPNomad\Di\Interfaces;

use PHPNomad\Di\Exceptions\DiException;

interface InstanceProvider
{
    /**
     * Get an instance of the class, with dependencies autowired
     *
     * @template T of object
     * @param class-string<T> $abstract
     * @return T
     * @throws DiException
     */
    public function get(string $abstract): object;
}