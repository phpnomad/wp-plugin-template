<?php

namespace PHPNomad\Rest\Interfaces;

interface RestStrategy
{
    /**
     * @param callable(): Controller $controllerGetter
     * @return mixed
     */
    public function registerRoute(callable $controllerGetter);
}