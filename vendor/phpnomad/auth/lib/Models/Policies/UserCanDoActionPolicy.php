<?php

namespace PHPNomad\Auth\Models\Policies;

use PHPNomad\Auth\Interfaces\AuthorizationPolicy;
use PHPNomad\Auth\Interfaces\Session;
use PHPNomad\Auth\Interfaces\User;

class UserCanDoActionPolicy implements AuthorizationPolicy
{
    protected array $requiredPermissions;

    public function evaluate(User $user, Session $session): bool
    {
        return $user->canDoAction($session->getIntendedAction());
    }
}