<?php

namespace PHPNomad\Auth\Interfaces;

interface CurrentUserResolverStrategy
{
    /**
     * Gets the current user.
     *
     * @return ?User The current user, or null if there's no current user.
     */
    public function getCurrentUser(): ?User;
}