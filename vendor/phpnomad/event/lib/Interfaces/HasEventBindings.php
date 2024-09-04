<?php

namespace PHPNomad\Events\Interfaces;

interface HasEventBindings
{
    /**
     * @return array
     */
    public function getEventBindings(): array;
}