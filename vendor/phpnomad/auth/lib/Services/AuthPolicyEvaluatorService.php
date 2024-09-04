<?php

namespace PHPNomad\Auth\Services;

use PHPNomad\Auth\Interfaces\AuthorizationPolicy;
use PHPNomad\Auth\Interfaces\Session;
use PHPNomad\Auth\Interfaces\User;

class AuthPolicyEvaluatorService
{
    /**
     * Evaluates a set of policies for a given user and session.
     *
     * @param AuthorizationPolicy[] $policies An array of PolicyInterface objects.
     * @param User $user
     * @param Session $session
     * @return bool True if access is granted, false otherwise.
     */
    public function evaluatePolicies(array $policies, User $user, Session $session): bool {
        foreach ($policies as $policy) {
            if (!$policy->evaluate($user, $session)) {
                return false;
            }
        }

        return true;
    }
}