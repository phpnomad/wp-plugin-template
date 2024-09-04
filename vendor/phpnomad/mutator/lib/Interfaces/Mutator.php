<?php

namespace PHPNomad\Mutator\Interfaces;

interface Mutator
{
    /**
     * Mutates using the provided arguments.
     *
     * @return void
     */
    public function mutate(): void;
}