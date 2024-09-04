<?php

namespace PHPNomad\Integrations\WordPress\Providers;

use PHPNomad\Auth\Interfaces\SecretProvider as SecretProviderInterface;

class SecretProvider implements SecretProviderInterface
{
    public function getSecret(): string
    {
        return AUTH_SALT;
    }
}