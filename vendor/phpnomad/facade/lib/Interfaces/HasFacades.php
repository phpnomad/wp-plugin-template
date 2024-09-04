<?php

namespace PHPNomad\Facade\Interfaces;

use PHPNomad\Facade\Abstracts\Facade;

interface HasFacades
{
    /**
     * @return Facade<object>[]
     */
    public function getFacades(): array;
}