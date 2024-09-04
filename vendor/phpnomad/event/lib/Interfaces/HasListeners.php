<?php

namespace PHPNomad\Events\Interfaces;

interface HasListeners
{
    /**
     * Gets the listeners, and the associated handlers for that listener.
     *
     * @return array<class-string<Event>, class-string<CanHandle>[]|class-string<CanHandle>>
     */
    public function getListeners(): array;
}