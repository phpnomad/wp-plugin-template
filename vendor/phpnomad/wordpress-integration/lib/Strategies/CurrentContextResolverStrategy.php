<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy as CurrentContextResolverStrategyInterface;

class CurrentContextResolverStrategy implements CurrentContextResolverStrategyInterface
{
    public function getCurrentContext(): string
    {
        $contextChecks = [
            SessionContexts::Rest => fn() => defined('REST_REQUEST') && REST_REQUEST,
            SessionContexts::Ajax => fn() => defined('DOING_AJAX') && DOING_AJAX,
            SessionContexts::CommandLine => fn() => defined('WP_CLI') && WP_CLI,
            SessionContexts::XmlRpc => fn() => defined('XMLRPC_REQUEST') && XMLRPC_REQUEST,
            SessionContexts::CronJob => fn() => defined('DOING_CRON') && DOING_CRON,
            SessionContexts::Admin => fn() => is_admin()
        ];

        foreach ($contextChecks as $context => $check) {
            if ($check()) {
                return $context;
            }
        }

        return SessionContexts::Web;
    }
}