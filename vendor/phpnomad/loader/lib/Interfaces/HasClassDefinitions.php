<?php

namespace PHPNomad\Loader\Interfaces;
interface HasClassDefinitions
{
    /**
     * Specifies class definitions
     *
     * @return array<class-string,class-string|class-string[]> One or multiple interfaces that are implemented by the key.
     */
    public function getClassDefinitions(): array;
}