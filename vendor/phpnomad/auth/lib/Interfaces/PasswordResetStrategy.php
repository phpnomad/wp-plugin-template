<?php

namespace PHPNomad\Auth\Interfaces;

use PHPNomad\Auth\Exceptions\AuthException;

interface PasswordResetStrategy
{
    /**
     * Retrieves the password reset link for the given user.
     *
     * @param User $user The user object for which to generate the password reset link.
     * @return string The password reset link URL as a string.
     * @throws AuthException
     */
    public function getPasswordResetLink(User $user): string;
}