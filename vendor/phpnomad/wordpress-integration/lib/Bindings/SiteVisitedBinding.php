<?php

namespace PHPNomad\Integrations\WordPress\Bindings;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy;
use PHPNomad\Auth\Interfaces\CurrentUserResolverStrategy;
use PHPNomad\Framework\Events\SiteVisited;
use PHPNomad\Utils\Helpers\Arr;

class SiteVisitedBinding
{
    protected CurrentContextResolverStrategy $contextResolver;
    protected CurrentUserResolverStrategy $userResolver;
    private static bool $ran = false;

    public function __construct(CurrentContextResolverStrategy $contextResolver, CurrentUserResolverStrategy $currentUserResolver)
    {
        $this->contextResolver = $contextResolver;
        $this->userResolver = $currentUserResolver;
    }

    public function __invoke(): ?SiteVisited
    {
        // Ensure this binding only runs once per request.
        if(static::$ran){
            return null;
        }

        static::$ran = true;

        $context = $this->contextResolver->getCurrentContext();
        $user    = $this->userResolver->getCurrentUser();

        if (is_admin() || $context !== SessionContexts::Web) {
            return null;
        }

        return new SiteVisited($user ? $user->getId() : null);
    }
}