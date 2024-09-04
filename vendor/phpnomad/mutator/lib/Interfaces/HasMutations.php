<?php

namespace PHPNomad\Mutator\Interfaces;

interface HasMutations{
    /**
     * @return array
     */
    public function getMutations(): array;
}