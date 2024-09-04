<?php

namespace PHPNomad\Rest\Middleware;

use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;

class CallbackMiddleware implements Middleware
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function process(Request $request): void
    {
        ($this->callback)($request);
    }
}