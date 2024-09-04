<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Interfaces\LoginUrlProvider as LoginUrlProviderInterface;

class LoginUrlProvider implements LoginUrlProviderInterface
{
    public function getRegistrationUrl(): string
    {
        return wp_registration_url();
    }
}