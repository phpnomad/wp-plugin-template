<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Interfaces\HashStrategy as HashStrategyInterface;

class HashStrategy implements HashStrategyInterface
{
    public function generateHash(int $characterCount = 32): string
    {
        return wp_generate_password($characterCount, false);
    }
}