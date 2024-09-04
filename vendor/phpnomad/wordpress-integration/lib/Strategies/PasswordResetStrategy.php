<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Exceptions\AuthException;
use PHPNomad\Auth\Interfaces\PasswordResetStrategy as PasswordResetStrategyInterface;
use PHPNomad\Auth\Interfaces\User;
use WP_Error;
use WP_User;

class PasswordResetStrategy implements PasswordResetStrategyInterface
{
    public function getPasswordResetLink(User $user): string
    {
        $user = new WP_User($user->getId());
        $key = get_password_reset_key($user);

        if ($key instanceof WP_Error) {
            throw new AuthException('Could not generate password reset key: ' . $key->get_error_message());
        }

        return add_query_arg(array(
            'action' => 'rp',
            'key' => $key,
            'login' => rawurlencode($user->user_login),
        ), wp_login_url());
    }
}