<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPNomad\Template\Interfaces\ScreenResolverStrategy;

class AdminScreenResolver implements ScreenResolverStrategy
{
    protected string $actionKey = 'siren_action';

    private function getContext(array $context = []): string
    {
        if(!empty($context)) {
            $query = http_build_query($context);
            $query = "&$query";
        }

        return $query ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getUrlForSlug(string $slug, array $context = []): string
    {
        return get_admin_url() . 'admin.php?page=' . $slug . $this->getContext($context);
    }

    /**
     * @inheritDoc
     */
    public function getUrlForAction(string $slug, string $action, array $context = []): string
    {
        $url = get_admin_url() . 'admin.php?page=' . $slug . '&' . $this->actionKey . '=' . $action . $this->getContext($context);

        return wp_nonce_url($url, 'siren_' . $slug . $action);
    }

    /**
     * @inheritDoc
     */
    public function isCurrentScreen(string $slug): bool
    {
        return $_REQUEST['page'] === $slug;
    }

    /**
     * @inheritDoc
     */
    public function isCurrentAction(string $slug, string $action): bool
    {
        return $this->isCurrentScreen($slug) && $_REQUEST[$this->actionKey] === $action;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentScreen(): ?string
    {
        return $_REQUEST['page'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentAction(): ?string
    {
        return $_REQUEST[$this->actionKey] ?? null;
    }
}