<?php

namespace PHPNomad\Auth\Interfaces;

interface HasUser
{
    /**
     * Get the user.
     *
     * @return User|null The user object if authenticated, or null if not authenticated.
     */
    public function getUser(): ?User;
}