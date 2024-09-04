<?php

namespace PHPNomad\Auth\Interfaces;

interface AuthorizationPolicy
{
    /**
     * Evaluates the policy against a given user and session.
     *
     * @param User $user
     * @param Session $session
     * @return bool True if the user is authorized, false otherwise.
     */
    public function evaluate(User $user, Session $session): bool;
}