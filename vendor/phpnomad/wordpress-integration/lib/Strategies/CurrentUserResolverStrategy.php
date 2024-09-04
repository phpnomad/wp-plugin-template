<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Interfaces\CurrentUserResolverStrategy as CurrentUserResolverStrategyInterface;
use PHPNomad\Integrations\WordPress\Auth\User;

class CurrentUserResolverStrategy implements CurrentUserResolverStrategyInterface
{
    /**
     * Get the current logged-in user.
     *
     * This method retrieves the current logged-in user and returns it as a User object.
     *
     * @return ?User The current logged-in user.
     */
    public function getCurrentUser(): ?User
    {
        $user = wp_get_current_user();
        return $user->ID > 0 ? new User($user) : null;
    }
}