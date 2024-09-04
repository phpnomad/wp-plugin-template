<?php

namespace PHPNomad\Auth\Models\Policies;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\AuthorizationPolicy;
use PHPNomad\Auth\Interfaces\Session;
use PHPNomad\Auth\Interfaces\User;

class SessionTypePolicy implements AuthorizationPolicy
{
    protected string $context;

    /**
     * @param SessionContexts::* $context
     */
    public function __construct(string $context)
    {
        $this->context = $context;
    }

    public function evaluate(User $user, Session $session): bool
    {
        return $session->getContext() === $this->context;
    }
}