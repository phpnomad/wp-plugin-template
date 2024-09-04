<?php

namespace PHPNomad\Mutator\Interfaces;

interface MutationAdapter{
    /**
     * Converts the given set of arguments into the mutator instance.
     *
     * @param ...$args
     * @return mixed
     */
    public function convertFromSource(...$args): Mutator;

    /**
     * Converts the given mutator into the value that should be returned by the mutator.
     *
     * @param Mutator $mutator
     * @return mixed
     */
    public function convertToResult(Mutator $mutator);
}
