<?php

namespace PHPNomad\Datastore\Interfaces;

interface CanIdentify
{

    /**
     * @return array<string, int> Identifiers for this item, keyed by the field name.
     */
    public function getIdentity(): array;
}