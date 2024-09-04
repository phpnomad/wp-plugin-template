<?php

namespace PHPNomad\Integrations\WordPress\Providers;

use PHPNomad\Cache\Interfaces\HasDefaultTtl;
use PHPNomad\Database\Interfaces\HasCharsetProvider;
use PHPNomad\Database\Interfaces\HasCollateProvider;
use PHPNomad\Database\Interfaces\HasGlobalDatabasePrefix;

class DatabaseProvider implements HasGlobalDatabasePrefix, HasCollateProvider, HasCharsetProvider
{
    /** @inheritDoc */
    public function getGlobalDatabasePrefix(): string
    {
        global $wpdb;

        return $wpdb->prefix;
    }

    public function getCharset(): ?string
    {
        return 'utf8mb4';
    }

    public function getCollation(): ?string
    {
        return 'utf8mb4_unicode_ci';
    }
}
