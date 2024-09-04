<?php

namespace PHPNomad\Rest\Interfaces;

interface HasControllers
{
    /**
     * @return class-string<Controller>[]
     */
    public function getControllers(): array;
}