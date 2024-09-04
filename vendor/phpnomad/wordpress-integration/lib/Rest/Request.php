<?php

namespace PHPNomad\Integrations\WordPress\Rest;

use PHPNomad\Auth\Interfaces\User;
use PHPNomad\Rest\Interfaces\Request as CoreRequest;
use PHPNomad\Utils\Helpers\Arr;
use WP_REST_Request;

final class Request implements CoreRequest
{
    private WP_REST_Request $request;
    protected ?User $user;

    public function __construct(WP_REST_Request $request, ?User $user = null)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /** @inheritDoc */
    public function getHeader(string $name): ?string
    {
        return $this->request->get_header($name);
    }

    /** @inheritDoc */
    public function setHeader(string $name, $value): void
    {
        $this->request->set_header($name, $value);
    }

    /** @inheritDoc */
    public function getHeaders(): array
    {
        return $this->request->get_headers();
    }

    /** @inheritDoc */
    public function getParam(string $name)
    {
        return Arr::dot($this->request->get_params(), $name);
    }

    /** @inheritDoc */
    public function setParam(string $name, $value): void
    {
        $this->request->set_param($name, $value);
    }

    /** @inheritDoc */
    public function getParams(): array
    {
        return $this->request->get_params();
    }

    /**
     * @inheritDoc
     */
    public function hasParam(string $name): bool
    {
        return Arr::has($this->request->get_params(), $name);
    }

    /**
     * @inheritDoc
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function removeParam(string $name): void
    {
        $this->request->offsetUnset($name);
    }
}
