<?php

namespace PHPNomad\Auth\Interfaces;

use PHPNomad\Datastore\Interfaces\DataModel;

interface User extends DataModel
{
    /**
     * Returns the unique identifier of the user.
     *
     * @return int|string The identifier for this user.
     */
    public function getId();

    /**
     * Gets the user's email address
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Checks if the user can perform the given action.
     *
     * @param Action $action The action to be checked.
     *
     * @return bool Returns true if the user can perform the action, false otherwise.
     */
    public function canDoAction(Action $action): bool;
}
