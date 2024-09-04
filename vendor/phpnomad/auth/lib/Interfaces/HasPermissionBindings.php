<?php

namespace PHPNomad\Auth\Interfaces;

interface HasPermissionBindings
{
    /**
     * Retrieves the permission bindings array.
     *
     * This method returns an array that contains the permission bindings.
     *
     * @return array The permission bindings array.
     */
    public function getPermissionBindings(): array;
}