<?php

namespace PHPNomad\Loader\Interfaces;

use PHPNomad\Loader\Exceptions\LoaderException;

interface Loadable
{
    /**
     * Loads this thing.
     * @return void
     * @throws LoaderException
     */
    public function load(): void;
}