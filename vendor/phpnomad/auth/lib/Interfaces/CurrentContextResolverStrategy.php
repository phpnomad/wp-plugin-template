<?php

namespace PHPNomad\Auth\Interfaces;

use PHPNomad\Auth\Enums\SessionContexts;

interface CurrentContextResolverStrategy
{
    /**
     * Gets the current context.
     *
     * @return SessionContexts::* The current context.
     */
    public function getCurrentContext(): string;
}