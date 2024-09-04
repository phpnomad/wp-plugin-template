<?php
namespace PHPNomad\Mutator\Interfaces;

interface MutationStrategy
{
    /**
     * @param callable():MutatorHandler $mutatorGetter
     * @param string $action
     * @return void
     */
    public function attach(callable $mutatorGetter, string $action): void;
}