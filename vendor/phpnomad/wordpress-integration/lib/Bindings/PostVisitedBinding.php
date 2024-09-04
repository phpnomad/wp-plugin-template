<?php

namespace PHPNomad\Integrations\WordPress\Bindings;

use PHPNomad\Auth\Enums\SessionContexts;
use PHPNomad\Auth\Interfaces\CurrentContextResolverStrategy;
use PHPNomad\Framework\Events\PostVisited;
use PHPNomad\Utils\Helpers\Arr;

class PostVisitedBinding
{
    protected CurrentContextResolverStrategy $contextResolver;

    public function __construct(CurrentContextResolverStrategy $contextResolver)
    {
       $this->contextResolver = $contextResolver;
    }

    public function __invoke(): ?PostVisited
    {
        global $post;
        $context = $this->contextResolver->getCurrentContext();

        if (is_single() && $post->post_type === 'post' &&  $context === SessionContexts::Web) {
            return new PostVisited($post->ID);
        }

        return null;
    }
}