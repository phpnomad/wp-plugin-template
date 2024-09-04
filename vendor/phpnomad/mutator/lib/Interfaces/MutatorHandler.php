<?php

namespace PHPNomad\Mutator\Interfaces;

interface MutatorHandler
{
    /**
     * Mutates using the provided arguments.
     *
     * @return mixed
     */
    public function mutate(...$args);
}