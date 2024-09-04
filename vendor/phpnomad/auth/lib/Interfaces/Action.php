<?php

namespace PHPNomad\Auth\Interfaces;

interface Action
{
    /**
     * Retrieves the target identifier for the given entity.
     *
     * This method returns an array containing the target identifier of the entity.
     *
     * @return array The target identifier as an array, or null if this action doesn't have an identifier.
     */
    public function getTargetIdentifier(): ?array;

    /**
     * Retrieves the target type for the given entity.
     *
     * This method returns the target type of the entity as a string.
     *
     * @return string The target type of the entity.
     */
    public function getTargetType(): string;

    /**
     * Retrieves the action associated with this object.
     *
     * @return string The action associated with this object.
     */
    public function getAction(): string;
}